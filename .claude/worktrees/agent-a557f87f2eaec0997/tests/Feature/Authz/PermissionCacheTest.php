<?php

declare(strict_types=1);

use App\Domain\Access\ResolvePermissions;
use App\Enums\RoleSlug;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(Database\Seeders\RolePermissionSeeder::class);
});

// M25-R11 — resolving permissions costs at most one query, and none once warm.
// What this replaces ran two queries and defined 162 Gate closures on every
// single request.

it('resolves from cache after the first call', function (): void {
    $user = User::factory()->staff()->create();
    $user->roles()->attach(Role::query()->where('slug', RoleSlug::RecruitmentAdmin->value)->firstOrFail());

    $resolver = app(ResolvePermissions::class);
    $resolver->for($user->load('roles'));

    DB::enableQueryLog();
    $resolver->for($user);
    $queries = DB::getQueryLog();
    DB::disableQueryLog();

    expect($queries)->toHaveCount(0);
});

// M25-R10 — a revocation that stays live for fifteen minutes is not a
// revocation.

it('invalidates the cache when a role changes', function (): void {
    $user = User::factory()->staff()->create();
    $role = Role::query()->where('slug', RoleSlug::RecruitmentAdmin->value)->firstOrFail();
    $user->roles()->attach($role);
    $user->load('roles');

    $resolver = app(ResolvePermissions::class);

    expect($resolver->has($user, 'advertisement.create'))->toBeTrue();

    $role->permissions()->detach();
    $role->touch();          // fires Role::saved, which invalidates every holder

    expect($resolver->has($user->fresh()->load('roles'), 'advertisement.create'))->toBeFalse();
});

it('gives super_admin every permission through the named role, not an id', function (): void {
    $admin = User::factory()->staff()->create();
    $admin->roles()->attach(Role::query()->where('slug', RoleSlug::SuperAdmin->value)->firstOrFail());
    $admin->load('roles');

    expect($admin->hasRole(RoleSlug::SuperAdmin))->toBeTrue()
        ->and(app(ResolvePermissions::class)->has($admin, 'audit.verify'))->toBeTrue();
});

it('gives a user with no role no permissions at all', function (): void {
    expect(app(ResolvePermissions::class)->for(User::factory()->create()))->toBe([]);
});
