<?php

declare(strict_types=1);

use App\Domain\Migration\DecomposeBlob;
use App\Domain\Migration\MapOrganisationalUnit;
use App\Domain\Migration\Quarantine;
use App\Domain\Migration\ReconcileCounts;
use App\Models\OrganisationalUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

// The longtext blobs that hold the real payload.

it('decomposes a legacy blob', function (): void {
    $result = (new DecomposeBlob)->handle('{"first_name":"Aisha","dob":"1996-06-01"}');

    expect($result['ok'])->toBeTrue()
        ->and($result['data']['first_name'])->toBe('Aisha');
});

it('unwraps the data wrapper some form versions wrote', function (): void {
    // Legacy blobs nest inconsistently depending on which version of the form
    // wrote them.
    $result = (new DecomposeBlob)->handle('{"data":{"first_name":"Aisha"}}');

    expect($result['data']['first_name'])->toBe('Aisha');
});

it('reports malformed JSON rather than skipping the row', function (): void {
    // Over 78,232 rows a silent skip is a candidate whose application
    // vanishes without anybody noticing.
    expect((new DecomposeBlob)->handle('{not json'))
        ->toBe(['ok' => false, 'reason' => 'malformed_json']);

    expect((new DecomposeBlob)->handle(null))
        ->toBe(['ok' => false, 'reason' => 'empty_blob']);
});

// Mapping the free-text department.

it('maps a post title to its organisational unit', function (): void {
    OrganisationalUnit::factory()->create(['title' => 'Department of History', 'code' => 'HIST']);

    $unit = (new MapOrganisationalUnit)->from('Assistant Professor, Department of History');

    expect($unit)->toBe((int) OrganisationalUnit::query()->where('code', 'HIST')->value('id'));
});

it('returns nothing rather than guessing when the title is ambiguous', function (): void {
    OrganisationalUnit::factory()->create(['title' => 'Physics', 'code' => 'PHY']);
    OrganisationalUnit::factory()->create(['title' => 'Zoology', 'code' => 'ZOO']);

    // The organisational unit is what the Dean-scoped authorisation reads, so
    // a wrong guess is an access-control defect and not a data-quality one.
    expect((new MapOrganisationalUnit)->from('Assistant Professor, Physics and Zoology'))->toBeNull();
});

it('returns nothing when no unit matches', function (): void {
    OrganisationalUnit::factory()->create(['title' => 'Department of History', 'code' => 'HIST']);

    expect((new MapOrganisationalUnit)->from('Assistant Professor, Dept of Conservative Dentistry'))
        ->toBeNull();
});

// Quarantine rather than skip.

it('holds an unmappable row and counts it as outstanding', function (): void {
    $quarantine = new Quarantine;

    $quarantine->hold('posts', 2599, 'unmappable_unit', ['title' => 'Assistant Professor, Dept of Endodontics']);

    expect($quarantine->outstanding('posts'))->toBe(1);
});

it('is idempotent on the source row', function (): void {
    $quarantine = new Quarantine;

    $quarantine->hold('posts', 2599, 'unmappable_unit', ['a' => 1]);
    $quarantine->hold('posts', 2599, 'unmappable_unit', ['a' => 2]);

    // A migration that cannot be re-run safely is one nobody dares re-run,
    // which makes every defect found halfway through a restore-from-backup.
    expect(DB::table('migration_quarantine')->count())->toBe(1);
});

it('stops counting a row once it has been resolved', function (): void {
    $quarantine = new Quarantine;
    $quarantine->hold('posts', 2599, 'unmappable_unit', []);

    DB::table('migration_quarantine')->update(['resolved_at' => now()]);

    expect($quarantine->outstanding('posts'))->toBe(0)
        ->and(DB::table('migration_quarantine')->count())->toBe(1);
});

// Reconciliation.

it('reports an unreachable source as unreachable, never as zero', function (): void {
    // Reporting zero would make an unreconciled migration look perfectly
    // reconciled.
    $report = (new ReconcileCounts)->handle();

    foreach ($report as $row) {
        expect($row['source_rows'])->toBeNull()
            ->and($row['reconciled'])->toBeFalse();
    }
});

it('carries a legacy id on every table it migrates into', function (): void {
    foreach (['users', 'applications', 'orders'] as $table) {
        expect(Schema::hasColumn($table, 'legacy_id'))->toBeTrue();
    }
});

it('refuses two rows claiming the same legacy id', function (): void {
    $first = App\Models\User::factory()->create();
    $second = App\Models\User::factory()->create();

    $first->forceFill(['legacy_id' => 42])->save();

    expect(fn () => $second->forceFill(['legacy_id' => 42])->save())
        ->toThrow(Illuminate\Database\QueryException::class);
});
