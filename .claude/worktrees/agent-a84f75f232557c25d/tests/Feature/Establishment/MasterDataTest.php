<?php

declare(strict_types=1);

use App\Enums\Cadre;
use App\Enums\SelectionMethod;
use App\Models\Category;
use App\Models\Designation;
use App\Models\DisabilityType;
use App\Models\HorizontalCategory;
use App\Models\PostType;
use App\Models\Province;
use App\Models\QualificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(Database\Seeders\MasterDataSeeder::class);
});

/**
 * Every lookup table in the previous build was empty after seeding, so every
 * dropdown in the system rendered blank. Emptiness is the defect under test.
 */
it('populates every reference master rather than merely creating it', function (): void {
    expect(Category::query()->count())->toBeGreaterThan(0)
        ->and(DisabilityType::query()->count())->toBeGreaterThan(0)
        ->and(QualificationLevel::query()->count())->toBeGreaterThan(0)
        ->and(PostType::query()->count())->toBeGreaterThan(0)
        ->and(Province::query()->count())->toBeGreaterThan(0);
});

it('carries EWS, which the 2018 Regulations omit entirely', function (): void {
    expect(Category::query()->where('code', 'EWS')->exists())->toBeTrue();
});

it('records that an OBC-NCL certificate expires and an SC one does not', function (): void {
    // Modelling expiry on the category is what lets validation ask for a
    // validity date only where one exists.
    expect(Category::query()->where('code', 'OBC-NCL')->value('certificate_expires'))->toBeTrue()
        ->and(Category::query()->where('code', 'SC')->value('certificate_expires'))->toBeFalse();
});

it('keeps vertical and horizontal categories in separate tables', function (): void {
    // A candidate is SC AND a person with disability, never one instead of the
    // other, so they cannot share an enum.
    expect(HorizontalCategory::query()->where('code', 'PWBD')->exists())->toBeTrue()
        ->and(Category::query()->where('code', 'PWBD')->exists())->toBeFalse();
});

it('carries the five disability categories of UGC 2018 cl. 3.4 I', function (): void {
    expect(DisabilityType::query()->count())->toBe(5);
});

it('carries an NCRF level, which the 2025 draft requires', function (): void {
    // Nullable under 2018 and required under the draft, so it is present from
    // the first migration: adding it later means re-opening submitted
    // applications.
    expect(QualificationLevel::query()->where('code', 'PG')->value('ncrf_level'))->toBe(7);
});

it('ships the seven post types of DR-007', function (): void {
    expect(PostType::query()->count())->toBe(7);
});

it('drives the active gates from the selection method, not all three', function (): void {
    // The legacy modal enabled all three gates even on interview-only types,
    // so an officer could record a written-test decision for a post with no
    // written test.
    $teaching = PostType::query()->where('code', 'TEACH-GEN')->firstOrFail();
    $nonTeaching = PostType::query()->where('code', 'NT-GEN')->firstOrFail();

    expect($teaching->activeGates())->toBe(['scrutiny', 'interview'])
        ->and($teaching->has_written_test_gate)->toBeFalse()
        ->and($nonTeaching->activeGates())->toContain('written_test');
});

it('distinguishes the General and Local regimes rather than treating them as duplicates', function (): void {
    // DR-010: different committees and administration, identical eligibility.
    $general = PostType::query()->where('code', 'TEACH-GEN')->firstOrFail();
    $local = PostType::query()->where('code', 'TEACH-LOC')->firstOrFail();

    expect($general->submission_venue)->not->toBe($local->submission_venue)
        ->and($local->submission_venue)->toContain('Dean');
});

it('seeds the designation spine with its statutory thresholds', function (): void {
    $this->seed(Database\Seeders\DesignationSeeder::class);

    $professor = Designation::query()->where('code', 'PROF')->firstOrFail();

    expect($professor->cadre)->toBe(Cadre::Teaching)
        ->and($professor->group)->toBeNull()
        ->and($professor->essential_qualification['research_score'])->toBe(120)
        ->and($professor->essential_qualification['citation'])->toContain('UGC 2018');
});

it('gives non-teaching designations a Group and teaching ones none', function (): void {
    $this->seed(Database\Seeders\DesignationSeeder::class);

    // A Group on a teaching cadre is a data error, not an empty field.
    expect(Designation::query()->where('code', 'ASSISTANT')->value('group'))->toBe('B')
        ->and(Designation::query()->where('code', 'ASST-PROF')->value('group'))->toBeNull();
});

it('keeps the screening score out of a teaching merit list', function (): void {
    // UGC 2018 cl. 4.1 Note and cl. 5.3: for teaching, merit is the interview
    // alone. A screening score entering it is a statutory violation, which is
    // why the rule lives on the cadre and not in a controller.
    expect(Cadre::Teaching->screeningScoreIsAdditive())->toBeFalse()
        ->and(Cadre::NonTeaching->screeningScoreIsAdditive())->toBeTrue();
});

it('gives every selection method a coherent gate set', function (): void {
    foreach (SelectionMethod::cases() as $method) {
        expect($method->activeGates())->toContain('scrutiny');
    }

    expect(SelectionMethod::InterviewOnly->activeGates())->not->toContain('written_test')
        ->and(SelectionMethod::TradeTest->activeGates())->not->toContain('interview');
});
