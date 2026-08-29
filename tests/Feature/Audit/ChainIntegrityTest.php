<?php

declare(strict_types=1);

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Domain\Audit\VerifyAuditChain;
use App\Enums\AuditEventName;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

function record(array $properties = ['k' => 'v']): AuditLog
{
    return app(RecordAuditEvent::class)->handle(
        new AuditEvent(event: AuditEventName::ModelCreated, properties: $properties)
    );
}

it('starts the chain from the genesis hash', function (): void {
    $first = record();

    expect($first->sequence)->toBe(1)
        ->and($first->previous_hash)->toBe(AuditLog::GENESIS_HASH);
});

it('links each entry to the one before it', function (): void {
    $first = record();
    $second = record();

    expect($second->previous_hash)->toBe($first->hash)
        ->and($second->sequence)->toBe(2);
});

it('verifies an untouched chain', function (): void {
    foreach (range(1, 20) as $i) {
        record(['i' => $i]);
    }

    $report = app(VerifyAuditChain::class)->handle();

    expect($report->intact)->toBeTrue()
        ->and($report->verified)->toBe(20)
        ->and($report->summary())->toContain('Chain verified, sequences 1–20');
});

// M26-R04 — a tampered row is reported by its exact sequence.

it('reports the exact sequence of an altered entry', function (): void {
    foreach (range(1, 10) as $i) {
        record(['i' => $i]);
    }

    // Straight past Eloquent and past the triggers, the way someone with a
    // database console would. This is the threat the chain exists to detect.
    dropAuditGuards();

    DB::table('audit_logs')->where('sequence', 6)
        ->update(['properties' => json_encode(['i' => 'tampered'])]);

    $report = app(VerifyAuditChain::class)->handle();

    expect($report->intact)->toBeFalse()
        ->and($report->brokenAt)->toBe(6)
        ->and($report->reason)->toContain('altered')
        ->and($report->summary())->toContain('Chain broken at sequence 6');
});

it('detects a deleted entry as a missing sequence', function (): void {
    foreach (range(1, 10) as $i) {
        record(['i' => $i]);
    }

    dropAuditGuards();

    DB::table('audit_logs')->where('sequence', 4)->delete();

    $report = app(VerifyAuditChain::class)->handle();

    expect($report->intact)->toBeFalse()
        ->and($report->brokenAt)->toBe(4)
        ->and($report->reason)->toContain('missing');
});

it('writes a checkpoint every ten thousand entries', function (): void {
    // Fast-forward the counter rather than writing 10,000 rows: the behaviour
    // under test is the interval, not the throughput.
    DB::table('audit_sequence')->where('id', 1)->update(['next_value' => 9_999]);

    record();                                   // 9,999 — no checkpoint
    expect(DB::table('audit_checkpoints')->count())->toBe(0);

    $checkpointed = record();                   // 10,000 — checkpoint

    expect($checkpointed->sequence)->toBe(10_000)
        ->and(DB::table('audit_checkpoints')->where('sequence', 10_000)->exists())->toBeTrue();
});
