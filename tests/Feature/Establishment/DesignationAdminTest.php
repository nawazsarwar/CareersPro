<?php

declare(strict_types=1);

use App\Enums\RoleSlug;
use App\Models\Designation;
use App\Models\OrganisationalUnit;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(Database\Seeders\RolePermissionSeeder::class);
});

function staffWith(RoleSlug $slug, ?OrganisationalUnit $unit = null): User
{
    $user = User::factory()->staff()->withTotp()->create();
    $user->roles()->attach(
        Role::query()->where('slug', $slug->value)->firstOrFail(),
        ['organisational_unit_id' => $unit?->getKey()],
    );

    return $user->load('roles');
}

it('lets a recruitment administrator create a designation', function (): void {
    $this->actingAs(staffWith(RoleSlug::RecruitmentAdmin))
        ->post(route('admin.designations.store'), [
            'code' => 'ASST-PROF-TEST',
            'name' => 'Assistant Professor',
            'cadre' => 'teaching',
            'pay_level' => 'A10',
            'selection_method' => 'interview_only',
        ])
        ->assertSessionHasNoErrors();

    expect(Designation::query()->where('code', 'ASST-PROF-TEST')->exists())->toBeTrue();
});

it('refuses a Group on a teaching cadre', function (): void {
    // A Group on a teaching cadre is a data error, not an empty field.
    $this->actingAs(staffWith(RoleSlug::RecruitmentAdmin))
        ->post(route('admin.designations.store'), [
            'code' => 'BAD-TEACH',
            'name' => 'Assistant Professor',
            'cadre' => 'teaching',
            'group' => 'A',
            'pay_level' => 'A10',
            'selection_method' => 'interview_only',
        ])
        ->assertSessionHasErrors(['group' => __('establishment.group_not_allowed')]);
});

it('requires a Group on a non-teaching cadre', function (): void {
    $this->actingAs(staffWith(RoleSlug::RecruitmentAdmin))
        ->post(route('admin.designations.store'), [
            'code' => 'BAD-NT',
            'name' => 'Section Officer',
            'cadre' => 'non_teaching',
            'pay_level' => 'L7',
            'selection_method' => 'written_skill_interview',
        ])
        ->assertSessionHasErrors(['group' => __('establishment.group_required')]);
});

it('refuses a maximum age below the minimum', function (): void {
    $this->actingAs(staffWith(RoleSlug::RecruitmentAdmin))
        ->post(route('admin.designations.store'), [
            'code' => 'BAD-AGE',
            'name' => 'Assistant',
            'cadre' => 'teaching',
            'pay_level' => 'A10',
            'selection_method' => 'interview_only',
            'min_age' => 40,
            'max_age' => 30,
        ])
        ->assertSessionHasErrors(['max_age' => __('establishment.age_order')]);
});

it('refuses a lowercase code', function (): void {
    $this->actingAs(staffWith(RoleSlug::RecruitmentAdmin))
        ->post(route('admin.designations.store'), [
            'code' => 'asst-prof',
            'name' => 'Assistant Professor',
            'cadre' => 'teaching',
            'pay_level' => 'A10',
            'selection_method' => 'interview_only',
        ])
        ->assertSessionHasErrors('code');
});

// M35 §6 — sanctioned strength is vested in the Executive Council by CRR
// Rule 8, not in a faculty.

it('gives a dean office user read access and no more', function (): void {
    $unit = OrganisationalUnit::factory()->create();
    $dean = staffWith(RoleSlug::DeanOfficeAdmin, $unit);
    $designation = Designation::factory()->create();

    $this->actingAs($dean)->get(route('admin.designations.index'))->assertOk();

    $this->actingAs($dean)
        ->patch(route('admin.establishment.update', [$unit, $designation]), [
            'sanctioned_count' => 5,
            'sanction_order_ref' => 'EC/2026/0117',
        ])
        ->assertForbidden();
});

it('requires the sanction order reference when the count changes', function (): void {
    $unit = OrganisationalUnit::factory()->create();
    $designation = Designation::factory()->create();

    // A sanctioned strength without its order reference is a number nobody can
    // defend to the Executive Council that supposedly approved it.
    $this->actingAs(staffWith(RoleSlug::RecruitmentAdmin))
        ->patch(route('admin.establishment.update', [$unit, $designation]), ['sanctioned_count' => 5])
        ->assertSessionHasErrors(['sanction_order_ref' => __('establishment.order_ref_required')]);
});

it('refuses to reduce sanctioned strength below the filled count', function (): void {
    $unit = OrganisationalUnit::factory()->create();
    $designation = Designation::factory()->create();

    DB::table('organisational_unit_designation')->insert([
        'organisational_unit_id' => $unit->getKey(),
        'designation_id' => $designation->getKey(),
        'sanctioned_count' => 5,
        'filled_count' => 3,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Reducing below the filled count would leave serving staff in posts the
    // University no longer sanctions.
    $this->actingAs(staffWith(RoleSlug::RecruitmentAdmin))
        ->patch(route('admin.establishment.update', [$unit, $designation]), [
            'sanctioned_count' => 2,
            'sanction_order_ref' => 'EC/2026/0118',
        ])
        ->assertSessionHasErrors('sanctioned_count');
});

it('records the sanctioned strength when the reference is given', function (): void {
    $unit = OrganisationalUnit::factory()->create();
    $designation = Designation::factory()->create();

    $this->actingAs(staffWith(RoleSlug::RecruitmentAdmin))
        ->patch(route('admin.establishment.update', [$unit, $designation]), [
            'sanctioned_count' => 4,
            'sanction_order_ref' => 'EC/2026/0117',
        ])
        ->assertSessionHasNoErrors();

    expect(DB::table('organisational_unit_designation')
        ->where('organisational_unit_id', $unit->getKey())
        ->value('sanctioned_count'))->toBe(4);
});

it('audits every designation change', function (): void {
    $this->actingAs(staffWith(RoleSlug::RecruitmentAdmin))
        ->post(route('admin.designations.store'), [
            'code' => 'AUDITED',
            'name' => 'Assistant Professor',
            'cadre' => 'teaching',
            'pay_level' => 'A10',
            'selection_method' => 'interview_only',
        ]);

    expect(App\Models\AuditLog::query()
        ->where('subject_type', (new Designation)->getMorphClass())
        ->where('event', 'model.created')
        ->exists())->toBeTrue();
});
