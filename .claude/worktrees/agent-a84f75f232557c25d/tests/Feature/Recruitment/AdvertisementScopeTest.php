<?php

declare(strict_types=1);

use App\Domain\Recruitment\PublishAdvertisement;
use App\Enums\RoleSlug;
use App\Models\Advertisement;
use App\Models\OrganisationalUnit;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(Database\Seeders\RolePermissionSeeder::class);
    $this->seed(Database\Seeders\MasterDataSeeder::class);

    $campus = OrganisationalUnit::factory()->create(['code' => 'CAMPUS']);
    $this->arts = OrganisationalUnit::factory()->childOf($campus)->create(['code' => 'ARTS']);
    $this->commerce = OrganisationalUnit::factory()->childOf($campus)->create(['code' => 'COMM']);
});

function scopedStaff(RoleSlug $slug, ?OrganisationalUnit $unit = null): User
{
    $user = User::factory()->staff()->withTotp()->create();
    $user->roles()->attach(
        Role::query()->where('slug', $slug->value)->firstOrFail(),
        ['organisational_unit_id' => $unit?->getKey()],
    );

    return $user->load('roles');
}

function localAdvertisementIn(OrganisationalUnit $unit): Advertisement
{
    $advertisement = Advertisement::factory()->local()->create(['organisational_unit_id' => $unit->getKey()]);
    Post::factory()->create([
        'advertisement_id' => $advertisement->getKey(),
        'organisational_unit_id' => $unit->getKey(),
    ]);

    return app(PublishAdvertisement::class)->handle($advertisement, User::factory()->staff()->create());
}

// M25-R02 — a Dean's-office user of Faculty X reaches nothing of Faculty Y.

it('shows a dean office user only their own faculty', function (): void {
    $arts = localAdvertisementIn($this->arts);
    $commerce = localAdvertisementIn($this->commerce);

    $this->actingAs(scopedStaff(RoleSlug::DeanOfficeAdmin, $this->arts))
        ->get(route('admin.advertisements.index'))
        ->assertOk()
        ->assertSee($arts->advertisement_no)
        ->assertDontSee($commerce->advertisement_no);
});

it('refuses a dean office user another faculty advertisement by its URL', function (): void {
    $commerce = localAdvertisementIn($this->commerce);

    $this->actingAs(scopedStaff(RoleSlug::DeanOfficeAdmin, $this->arts))
        ->get(route('admin.advertisements.show', $commerce))
        ->assertForbidden();
});

// M25-R03 — General recruitment is centrally administered (DR-010).

it('refuses a dean office user a General advertisement', function (): void {
    $general = Advertisement::factory()->create(['organisational_unit_id' => null]);
    Post::factory()->create(['advertisement_id' => $general->getKey()]);
    app(PublishAdvertisement::class)->handle($general, User::factory()->staff()->create());

    $this->actingAs(scopedStaff(RoleSlug::DeanOfficeAdmin, $this->arts))
        ->get(route('admin.advertisements.show', $general))
        ->assertForbidden();
});

it('shows a central administrator everything', function (): void {
    $arts = localAdvertisementIn($this->arts);
    $commerce = localAdvertisementIn($this->commerce);

    $this->actingAs(scopedStaff(RoleSlug::RecruitmentAdmin))
        ->get(route('admin.advertisements.index'))
        ->assertOk()
        ->assertSee($arts->advertisement_no)
        ->assertSee($commerce->advertisement_no);
});

// M25-R05 — no filter, sort or page combination exceeds the actor's scope.

it('cannot be widened past the scope by a page parameter', function (): void {
    localAdvertisementIn($this->commerce);

    $this->actingAs(scopedStaff(RoleSlug::DeanOfficeAdmin, $this->arts))
        ->get(route('admin.advertisements.index', ['page' => 1]))
        ->assertOk()
        ->assertDontSee('COMM');
});

// A published advertisement is not editable by anybody.

it('refuses to update a published advertisement', function (): void {
    $advertisement = localAdvertisementIn($this->arts);
    $admin = scopedStaff(RoleSlug::RecruitmentAdmin);

    expect($admin->can('update', $advertisement))->toBeFalse()
        ->and($admin->can('issueCorrigendum', $advertisement))->toBeTrue();
});

it('allows editing a draft', function (): void {
    $draft = Advertisement::factory()->create();

    expect(scopedStaff(RoleSlug::RecruitmentAdmin)->can('update', $draft))->toBeTrue();
});

it('refuses a corrigendum on a draft', function (): void {
    $draft = Advertisement::factory()->create();

    expect(scopedStaff(RoleSlug::RecruitmentAdmin)->can('issueCorrigendum', $draft))->toBeFalse();
});

it('requires an organisational unit on a local advertisement', function (): void {
    // Local recruitment is administered at faculty level, so without a unit
    // there is nobody the scoped roles resolve to.
    $this->actingAs(scopedStaff(RoleSlug::RecruitmentAdmin))
        ->post(route('admin.advertisements.store'), [
            'advertisement_no' => '99/2026/LOCAL',
            'title' => 'Local vacancy',
            'advertisement_type_id' => App\Models\AdvertisementType::query()->value('id'),
            'appointment_nature' => 'local',
            'default_opening_date' => now()->toDateString(),
            'default_closing_date' => now()->addDays(45)->toDateString(),
        ])
        ->assertSessionHasErrors(['organisational_unit_id' => __('recruitment.unit_required_for_local')]);
});

it('refuses a payment deadline after the closing date', function (): void {
    // A candidate who can pay after the closing date has been told the
    // deadline is later than it is.
    $this->actingAs(scopedStaff(RoleSlug::RecruitmentAdmin))
        ->post(route('admin.advertisements.store'), [
            'advertisement_no' => '98/2026/NT',
            'title' => 'General vacancy',
            'advertisement_type_id' => App\Models\AdvertisementType::query()->value('id'),
            'appointment_nature' => 'general',
            'default_opening_date' => now()->toDateString(),
            'default_closing_date' => now()->addDays(45)->toDateString(),
            'default_payment_closing_date' => now()->addDays(50)->toDateString(),
        ])
        ->assertSessionHasErrors('default_payment_closing_date');
});
