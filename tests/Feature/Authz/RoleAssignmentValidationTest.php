<?php

declare(strict_types=1);

use App\Enums\RoleSlug;
use App\Models\OrganisationalUnit;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(Database\Seeders\RolePermissionSeeder::class);

    $this->admin = User::factory()->staff()->withTotp()->create();
    $this->admin->roles()->attach(Role::query()->where('slug', RoleSlug::SuperAdmin->value)->firstOrFail());
    $this->admin->load('roles');

    $this->target = User::factory()->staff()->create();
    $this->unit = OrganisationalUnit::factory()->create();

    $this->session(['auth.password_confirmed_at' => time()]);
});

function roleId(RoleSlug $slug): int
{
    return (int) Role::query()->where('slug', $slug->value)->value('id');
}

// M25-R12 — an OU-scoped role without its unit is refused.

it('refuses a dean office role assigned without an organisational unit', function (): void {
    $this->actingAs($this->admin)
        ->post(route('admin.users.roles.attach', $this->target), [
            'role_id' => roleId(RoleSlug::DeanOfficeScrutiny),
        ])
        ->assertSessionHasErrors(['organisational_unit_id' => __('access.unit_required')]);

    expect($this->target->fresh()->roles)->toHaveCount(0);
});

it('accepts a dean office role with a published unit', function (): void {
    $this->actingAs($this->admin)
        ->post(route('admin.users.roles.attach', $this->target), [
            'role_id' => roleId(RoleSlug::DeanOfficeScrutiny),
            'organisational_unit_id' => $this->unit->getKey(),
        ])
        ->assertSessionHasNoErrors();

    expect($this->target->fresh()->roles)->toHaveCount(1);
});

it('refuses a university-wide role limited to a unit', function (): void {
    $this->actingAs($this->admin)
        ->post(route('admin.users.roles.attach', $this->target), [
            'role_id' => roleId(RoleSlug::RecruitmentAdmin),
            'organisational_unit_id' => $this->unit->getKey(),
        ])
        ->assertSessionHasErrors(['organisational_unit_id' => __('access.unit_not_allowed')]);
});

it('refuses an unpublished unit', function (): void {
    $draft = OrganisationalUnit::factory()->create(['status' => 'draft']);

    $this->actingAs($this->admin)
        ->post(route('admin.users.roles.attach', $this->target), [
            'role_id' => roleId(RoleSlug::DeanOfficeScrutiny),
            'organisational_unit_id' => $draft->getKey(),
        ])
        ->assertSessionHasErrors(['organisational_unit_id' => __('access.unit_unpublished')]);
});

it('refuses role assignment by anybody but a super administrator', function (): void {
    $recruiter = User::factory()->staff()->withTotp()->create();
    $recruiter->roles()->attach(Role::query()->where('slug', RoleSlug::RecruitmentAdmin->value)->firstOrFail());

    $this->actingAs($recruiter->load('roles'))
        ->post(route('admin.users.roles.attach', $this->target), [
            'role_id' => roleId(RoleSlug::RecruitmentAdmin),
        ])
        ->assertForbidden();
});

it('refuses a user assigning a role to themselves', function (): void {
    $this->actingAs($this->admin)
        ->post(route('admin.users.roles.attach', $this->admin), [
            'role_id' => roleId(RoleSlug::RecruitmentAdmin),
        ])
        ->assertForbidden();
});
