<?php

declare(strict_types=1);

use App\Domain\Application\SubmitApplication;
use App\Domain\Recruitment\PublishAdvertisement;
use App\Enums\RoleSlug;
use App\Models\Advertisement;
use App\Models\OrganisationalUnit;
use App\Models\Post;
use App\Models\Profile;
use App\Models\QualificationLevel;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(Database\Seeders\RolePermissionSeeder::class);
    $this->seed(Database\Seeders\MasterDataSeeder::class);
});

function candidateWithRole(): User
{
    $user = User::factory()->candidate()->withVerifiedMobile()->create();
    $user->roles()->attach(Role::query()->where('slug', RoleSlug::Candidate->value)->firstOrFail());

    Profile::query()->where('user_id', $user->getKey())->update([
        'first_name' => 'Aisha', 'last_name' => 'Khan',
        'dob' => now()->subYears(30)->toDateString(), 'gender' => 'female',
    ]);

    $user->academicQualifications()->create([
        'qualification_level_id' => QualificationLevel::query()->where('code', 'PG')->value('id'),
        'course' => 'M.A.', 'year_of_passing' => 2018, 'percentage' => 62.5,
    ]);

    return $user->refresh()->load('roles');
}

function postIn(?OrganisationalUnit $unit = null): Post
{
    $advertisement = Advertisement::factory()
        ->when($unit !== null, fn ($f) => $f->local())
        ->create(['organisational_unit_id' => $unit?->getKey()]);

    $post = Post::factory()->create([
        'advertisement_id' => $advertisement->getKey(),
        'organisational_unit_id' => $unit?->getKey(),
    ]);

    app(PublishAdvertisement::class)->handle($advertisement, User::factory()->staff()->create());

    return $post->refresh();
}

// M25-R01 — the defect this whole model exists to close.

it('refuses one candidate another candidate application', function (): void {
    $aisha = candidateWithRole();
    $other = candidateWithRole();

    $theirs = app(SubmitApplication::class)->handle($other, postIn());

    $this->actingAs($aisha)->get(route('frontend.applications.show', $theirs))->assertForbidden();
});

it('lists only the candidate own applications', function (): void {
    $aisha = candidateWithRole();
    $other = candidateWithRole();

    $mine = app(SubmitApplication::class)->handle($aisha, postIn());
    $theirs = app(SubmitApplication::class)->handle($other, postIn());

    $this->actingAs($aisha)
        ->get(route('frontend.applications.index'))
        ->assertOk()
        ->assertSee($mine->application_no)
        ->assertDontSee($theirs->application_no);
});

// M25-R02 — staff reach applications inside their subtree and no others.

it('scopes staff to their organisational subtree', function (): void {
    $campus = OrganisationalUnit::factory()->create();
    $arts = OrganisationalUnit::factory()->childOf($campus)->create(['code' => 'ARTS']);
    $commerce = OrganisationalUnit::factory()->childOf($campus)->create(['code' => 'COMM']);

    $inArts = app(SubmitApplication::class)->handle(candidateWithRole(), postIn($arts));
    $inCommerce = app(SubmitApplication::class)->handle(candidateWithRole(), postIn($commerce));

    $dean = User::factory()->staff()->withTotp()->create();
    $dean->roles()->attach(
        Role::query()->where('slug', RoleSlug::DeanOfficeScrutiny->value)->firstOrFail(),
        ['organisational_unit_id' => $arts->getKey()],
    );
    $dean->load('roles');

    expect($dean->can('view', $inArts))->toBeTrue()
        ->and($dean->can('view', $inCommerce))->toBeFalse();
});

it('keeps a submitted application uneditable', function (): void {
    $aisha = candidateWithRole();
    $application = app(SubmitApplication::class)->handle($aisha, postIn());

    // The dossier is locked from submission. A deficiency reopens a specific
    // field for a bounded window; the legacy hard lock after payment had no
    // way back at all.
    expect($aisha->can('update', $application))->toBeFalse()
        ->and($aisha->can('withdraw', $application))->toBeTrue();
});

it('requires authentication to reach the apply screen', function (): void {
    $post = postIn();

    $this->get(route('frontend.applications.create', $post->slug))
        ->assertRedirect(route('frontend.login'));
});

it('warns before payment rather than after', function (): void {
    // The legacy portal took the fee first and evaluated eligibility
    // afterwards, so a candidate who was never eligible paid to find out.
    $post = postIn();
    $post->update(['age_limit' => 25]);

    $aisha = candidateWithRole();

    $this->actingAs($aisha)
        ->get(route('frontend.applications.create', $post->slug))
        ->assertOk()
        ->assertSee(__('application.preflight_heading'));
});

it('computes age against the closing date, never today', function (): void {
    // CRR Rule 14. A candidate inside the limit on the closing date and
    // outside it by the time scrutiny happens is eligible, and computing
    // against now() would reject them for the University's own delay.
    $age = app(App\Domain\Dossier\ComputeAge::class);

    $dob = Carbon\CarbonImmutable::parse('1996-06-01');
    $closing = Carbon\CarbonImmutable::parse('2026-05-01');

    expect($age->on($dob, $closing))->toBe(29)
        ->and($age->exceedsLimit($dob, $closing, 30))->toBeFalse()
        ->and($age->exceedsLimit($dob, $closing, 28))->toBeTrue();
});

it('treats a declared CGPA conversion as provisional until proved', function (): void {
    // DR-016: cl. 3.6 defers to the awarding university's own formula, so
    // there is no single algorithm and the difference between 54.9% and 55%
    // is the difference between eligible and not.
    $normalise = app(App\Domain\Dossier\NormalisePercentage::class);

    $user = candidateWithRole();
    $qualification = $user->academicQualifications()->create([
        'qualification_level_id' => QualificationLevel::query()->where('code', 'UG')->value('id'),
        'cgpa' => 6.28,
        'cgpa_scale' => 10,
        'conversion_declaration' => ['multiplier' => 10, 'offset' => 0.75],
    ]);

    expect($normalise->from($qualification))->toBe(55.3)
        ->and($normalise->isProvisional($qualification))->toBeTrue();
});

it('prefers a stated percentage over a declared conversion', function (): void {
    $normalise = app(App\Domain\Dossier\NormalisePercentage::class);

    $user = candidateWithRole();
    $qualification = $user->academicQualifications()->first();

    // It is what the certificate says.
    expect($normalise->from($qualification))->toBe(62.5)
        ->and($normalise->isProvisional($qualification))->toBeFalse();
});
