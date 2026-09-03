<?php

declare(strict_types=1);

use App\Domain\Access\ResolveScopes;
use App\Enums\RoleSlug;
use App\Models\OrganisationalUnit;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(Database\Seeders\RolePermissionSeeder::class);

    // The three-level tree M25 §10 asks for: a campus, two faculties, and
    // departments under each.
    $this->campus = OrganisationalUnit::factory()->create(['code' => 'CAMPUS']);
    $this->arts = OrganisationalUnit::factory()->childOf($this->campus)->create(['code' => 'ARTS']);
    $this->commerce = OrganisationalUnit::factory()->childOf($this->campus)->create(['code' => 'COMM']);
    $this->history = OrganisationalUnit::factory()->childOf($this->arts)->create(['code' => 'HIST']);
});

function assign(User $user, RoleSlug $slug, ?OrganisationalUnit $unit = null): void
{
    $role = Role::query()->where('slug', $slug->value)->firstOrFail();

    $user->roles()->attach($role, ['organisational_unit_id' => $unit?->getKey()]);
    $user->load('roles');
}

it('materialises a path for every level of the tree', function (): void {
    expect($this->campus->fresh()->path)->toBe('/'.$this->campus->getKey().'/')
        ->and($this->arts->fresh()->path)->toBe('/'.$this->campus->getKey().'/'.$this->arts->getKey().'/')
        ->and($this->history->fresh()->path)
        ->toBe('/'.$this->campus->getKey().'/'.$this->arts->getKey().'/'.$this->history->getKey().'/');
});

it('moves every descendant when a unit is re-parented', function (): void {
    $this->arts->update(['parent_id' => null]);

    expect($this->arts->fresh()->path)->toBe('/'.$this->arts->getKey().'/')
        ->and($this->history->fresh()->path)
        ->toBe('/'.$this->arts->getKey().'/'.$this->history->getKey().'/');
});

// M25-R02 — a Dean's-office user of Faculty X reaches nothing of Faculty Y.

it('scopes a dean office user to their faculty and its children', function (): void {
    $rehman = User::factory()->staff()->create();
    assign($rehman, RoleSlug::DeanOfficeScrutiny, $this->arts);

    $paths = app(ResolveScopes::class)->for($rehman);

    expect($paths)->toBe([$this->arts->fresh()->path]);

    $reachable = OrganisationalUnit::query()->inSubtreeOf($paths[0])->pluck('code')->all();

    expect($reachable)->toContain('ARTS', 'HIST')
        ->and($reachable)->not->toContain('COMM');
});

it('returns null for a university-wide role, not an empty list', function (): void {
    $admin = User::factory()->staff()->create();
    assign($admin, RoleSlug::RecruitmentAdmin);

    // The distinction matters: null means "no path filter"; [] would mean
    // "reaches nothing", which would lock out exactly the people who run the
    // system.
    expect(app(ResolveScopes::class)->for($admin))->toBeNull()
        ->and(app(ResolveScopes::class)->isUniversityWide($admin))->toBeTrue();
});

it('treats one university-wide assignment as widening the whole actor', function (): void {
    $user = User::factory()->staff()->create();
    assign($user, RoleSlug::DeanOfficeScrutiny, $this->arts);
    assign($user, RoleSlug::RecruitmentAdmin);

    // Scopes widen; they do not intersect.
    expect(app(ResolveScopes::class)->for($user))->toBeNull();
});

it('gives a user with no role no scope at all', function (): void {
    expect(app(ResolveScopes::class)->for(User::factory()->create()))->toBe([]);
});

// M25-R01 — candidate A reaches nothing of candidate B's.

it('refuses a candidate access to another candidate profile', function (): void {
    $aisha = User::factory()->candidate()->withProfile()->create();
    $other = User::factory()->candidate()->withProfile()->create();

    assign($aisha, RoleSlug::Candidate);
    assign($other, RoleSlug::Candidate);

    $ownProfile = Profile::query()->where('user_id', $aisha->getKey())->firstOrFail();
    $otherProfile = Profile::query()->where('user_id', $other->getKey())->firstOrFail();

    expect($aisha->can('view', $ownProfile))->toBeTrue()
        ->and($aisha->can('update', $ownProfile))->toBeTrue()
        ->and($aisha->can('view', $otherProfile))->toBeFalse()
        ->and($aisha->can('update', $otherProfile))->toBeFalse()
        ->and($aisha->can('export', $otherProfile))->toBeFalse();
});

// M25-R03 — General recruitment is centrally administered, so a scoped user
// reaches none of it.

it('refuses a dean office user a row with no organisational unit', function (): void {
    $rehman = User::factory()->staff()->create();
    assign($rehman, RoleSlug::DeanOfficeScrutiny, $this->arts);

    $central = User::factory()->candidate()->withProfile()->create();
    $profile = Profile::query()->where('user_id', $central->getKey())->firstOrFail();

    // A profile carries no organisational unit, so it is outside every subtree.
    expect($rehman->can('view', $profile))->toBeFalse();
});

// M25-R13, M25-R14 — DR-015 splits the Dean's office three ways.

it('separates viewing, deciding and creating inside one faculty', function (): void {
    $permissions = app(App\Domain\Access\ResolvePermissions::class);

    $admin = User::factory()->staff()->create();
    $scrutiny = User::factory()->staff()->create();
    $viewer = User::factory()->staff()->create();

    assign($admin, RoleSlug::DeanOfficeAdmin, $this->arts);
    assign($scrutiny, RoleSlug::DeanOfficeScrutiny, $this->arts);
    assign($viewer, RoleSlug::DeanOfficeView, $this->arts);

    // R13: scrutiny alone cannot create an advertisement.
    expect($permissions->has($scrutiny, 'advertisement.create'))->toBeFalse()
        ->and($permissions->has($admin, 'advertisement.create'))->toBeTrue();

    // R14: view alone cannot decide a gate.
    expect($permissions->has($viewer, 'scrutiny.decide'))->toBeFalse()
        ->and($permissions->has($scrutiny, 'scrutiny.decide'))->toBeTrue();
});
