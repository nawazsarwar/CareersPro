<?php

declare(strict_types=1);

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Enums\AuditEventName;
use App\Models\AuditLog;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(RecordAuditEvent::class)->handle(
        new AuditEvent(event: AuditEventName::ModelCreated, properties: ['k' => 'v'])
    );
});

// M26-R02 and M26-R03 — the guarantee is the database's, not the ORM's.
// A guard that only holds when the application is the one writing is not a
// guard against the threat the chain exists to detect.

it('refuses an UPDATE at the database, bypassing the ORM entirely', function (): void {
    expect(fn () => DB::table('audit_logs')->where('sequence', 1)->update(['event' => 'forged']))
        ->toThrow(QueryException::class, 'append-only');
});

it('refuses a DELETE at the database, bypassing the ORM entirely', function (): void {
    expect(fn () => DB::table('audit_logs')->where('sequence', 1)->delete())
        ->toThrow(QueryException::class, 'append-only');
});

it('refuses to save an existing entry through the model', function (): void {
    $entry = AuditLog::query()->firstOrFail();
    $entry->event = 'forged';

    expect(fn () => $entry->save())->toThrow(LogicException::class, 'append-only');
});

it('refuses to delete through the model', function (): void {
    $entry = AuditLog::query()->firstOrFail();

    expect(fn () => $entry->delete())->toThrow(LogicException::class, 'append-only');
});

it('has no updated_at column to change', function (): void {
    expect(Schema::hasColumn('audit_logs', 'updated_at'))->toBeFalse()
        ->and(Schema::hasColumn('audit_logs', 'deleted_at'))->toBeFalse();
});
