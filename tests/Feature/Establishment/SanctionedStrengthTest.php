<?php

declare(strict_types=1);

use App\Domain\Establishment\AssertRule343;
use App\Domain\Establishment\AvailableVacancies;
use App\Domain\Establishment\SanctionedStrength;
use App\Models\Designation;
use App\Models\OrganisationalUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

function sanction(OrganisationalUnit $unit, Designation $designation, int $count, int $filled = 0): void
{
    DB::table('organisational_unit_designation')->insert([
        'organisational_unit_id' => $unit->getKey(),
        'designation_id' => $designation->getKey(),
        'sanctioned_count' => $count,
        'filled_count' => $filled,
        'sanction_order_ref' => 'EC/2026/0117',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

it('reads the sanctioned strength for a unit and designation', function (): void {
    $unit = OrganisationalUnit::factory()->create();
    $designation = Designation::factory()->create();

    sanction($unit, $designation, 4, 1);

    expect(app(SanctionedStrength::class)->for($unit, $designation))->toBe(4)
        ->and(app(SanctionedStrength::class)->filled($unit, $designation))->toBe(1);
});

it('computes vacancies as sanctioned minus filled minus advertised', function (): void {
    $unit = OrganisationalUnit::factory()->create();
    $designation = Designation::factory()->create();

    sanction($unit, $designation, 5, 2);

    expect(app(AvailableVacancies::class)->for($unit, $designation, advertisedAndOpen: 1))->toBe(2);
});

it('refuses to return a negative, because over-advertising is a hard error', function (): void {
    $unit = OrganisationalUnit::factory()->create();
    $designation = Designation::factory()->create();

    sanction($unit, $designation, 2, 2);

    // Advertising a post the University has not sanctioned is a commitment it
    // cannot lawfully honour, and the candidates who apply to it have a claim.
    expect(fn () => app(AvailableVacancies::class)->for($unit, $designation, advertisedAndOpen: 1))
        ->toThrow(RuntimeException::class, 'overcommitted');
});

// CRR Rule 34.3.

it('forces direct recruitment where only one post is sanctioned', function (): void {
    $unit = OrganisationalUnit::factory()->create();
    $designation = Designation::factory()->create();

    sanction($unit, $designation, 1);

    $rule = app(AssertRule343::class);

    // A single-post cadre has no promotion ladder: promoting into it would
    // promote somebody into the only post there is.
    expect(fn () => $rule->check($unit, $designation, 'promotion'))
        ->toThrow(RuntimeException::class, 'Rule 34.3');

    $rule->check($unit, $designation, AssertRule343::DIRECT_RECRUITMENT);
    expect(true)->toBeTrue();
});

it('allows promotion where more than one post is sanctioned', function (): void {
    $unit = OrganisationalUnit::factory()->create();
    $designation = Designation::factory()->create();

    sanction($unit, $designation, 3);

    app(AssertRule343::class)->check($unit, $designation, 'promotion');

    expect(true)->toBeTrue();
});

it('keeps one register row per unit and designation', function (): void {
    $unit = OrganisationalUnit::factory()->create();
    $designation = Designation::factory()->create();

    sanction($unit, $designation, 3);

    expect(fn () => sanction($unit, $designation, 4))
        ->toThrow(Illuminate\Database\QueryException::class);
});
