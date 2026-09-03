<?php

declare(strict_types=1);

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Domain\Audit\SequenceAllocator;
use App\Enums\AuditEventName;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

// M26-R05 — the sequence is gapless. A gap is indistinguishable from a deleted
// record, which is precisely the claim the chain exists to refute.

it('allocates without gaps across many writes', function (): void {
    foreach (range(1, 200) as $i) {
        app(RecordAuditEvent::class)->handle(
            new AuditEvent(event: AuditEventName::ModelCreated, properties: ['i' => $i])
        );
    }

    $sequences = AuditLog::query()->orderBy('sequence')->pluck('sequence')->all();

    expect($sequences)->toBe(range(1, 200))
        ->and(count(array_unique($sequences)))->toBe(200);
});

it('returns the number when the surrounding write fails, rather than burning it', function (): void {
    app(RecordAuditEvent::class)->handle(
        new AuditEvent(event: AuditEventName::ModelCreated, properties: ['first' => true])
    );

    // This is the case auto-increment cannot handle: it burns the value on
    // rollback and leaves a hole that reads as a deleted entry.
    try {
        DB::transaction(function (): void {
            app(SequenceAllocator::class)->next();

            throw new RuntimeException('the surrounding write failed');
        });
    } catch (RuntimeException) {
        // expected
    }

    app(RecordAuditEvent::class)->handle(
        new AuditEvent(event: AuditEventName::ModelCreated, properties: ['second' => true])
    );

    expect(AuditLog::query()->orderBy('sequence')->pluck('sequence')->all())->toBe([1, 2]);
});

it('refuses to allocate outside a transaction', function (): void {
    // Asserted against a stub rather than the live connection: RefreshDatabase
    // wraps every test in a transaction, so the real connection can never
    // report level 0 here. The guard itself is what is under test.
    $connection = Mockery::mock(Illuminate\Database\ConnectionInterface::class);
    $connection->shouldReceive('transactionLevel')->andReturn(0);

    expect(fn () => (new SequenceAllocator($connection))->next())
        ->toThrow(RuntimeException::class, 'inside a transaction');
});
