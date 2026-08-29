<?php

declare(strict_types=1);

use App\Enums\RoleSlug;
use App\Models\Role;
use App\Models\User;
use App\Policies\RuleSetPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(Database\Seeders\RolePermissionSeeder::class);
});

function asRole(RoleSlug $slug): User
{
    $user = User::factory()->staff()->create();
    $user->roles()->attach(Role::query()->where('slug', $slug->value)->firstOrFail());

    return $user->load('roles');
}

/**
 * M25-R06, M25-R07.
 *
 * This is the control that would have stopped a fabricated ruleset reaching
 * production. The previous work's rules file asserted that a Principal
 * Investigator scores 100% where the Gazette says 50% each; every Associate
 * Professor and Professor determination made under it would have been wrong,
 * and wrong in a direction a rejected candidate can challenge.
 */
it('lets rules_admin author but never activate', function (): void {
    $author = asRole(RoleSlug::RulesAdmin);
    $policy = app(RuleSetPolicy::class);

    $version = (object) ['authored_by_id' => (int) $author->getKey()];

    expect($policy->author($author))->toBeTrue()
        ->and($policy->activate($author, $version))->toBeFalse();
});

it('lets rules_verifier activate but never author', function (): void {
    $verifier = asRole(RoleSlug::RulesVerifier);
    $policy = app(RuleSetPolicy::class);

    $version = (object) ['authored_by_id' => 999];

    expect($policy->author($verifier))->toBeFalse()
        ->and($policy->activate($verifier, $version))->toBeTrue();
});

it('refuses activation by the user who authored it', function (): void {
    $verifier = asRole(RoleSlug::RulesVerifier);
    $policy = app(RuleSetPolicy::class);

    // Second-reader verification means a second reader. Holding both
    // permissions is not the same as being two people.
    $ownWork = (object) ['authored_by_id' => (int) $verifier->getKey()];

    expect($policy->activate($verifier, $ownWork))->toBeFalse();
});

it('applies the second-reader rule to super_admin as well', function (): void {
    $admin = asRole(RoleSlug::SuperAdmin);
    $policy = app(RuleSetPolicy::class);

    // super_admin holds every permission, including both halves of this one.
    // If the rule stopped at "has the permission", the separation would be
    // decorative.
    expect($policy->activate($admin, (object) ['authored_by_id' => (int) $admin->getKey()]))->toBeFalse()
        ->and($policy->activate($admin, (object) ['authored_by_id' => 999]))->toBeTrue();
});

it('keeps finance away from candidate personal data', function (): void {
    $finance = asRole(RoleSlug::FinanceAdmin);
    $permissions = app(App\Domain\Access\ResolvePermissions::class);

    // security-model.md §3.1: no PII beyond name and application number.
    expect($permissions->has($finance, 'order.reconcile'))->toBeTrue()
        ->and($permissions->has($finance, 'profile.view'))->toBeFalse()
        ->and($permissions->has($finance, 'document.view'))->toBeFalse();
});

it('gives the auditor no mutation anywhere', function (): void {
    $auditor = asRole(RoleSlug::Auditor);
    $permissions = app(App\Domain\Access\ResolvePermissions::class);

    foreach ($permissions->for($auditor) as $slug) {
        expect($slug)->not->toContain('.create')
            ->and($slug)->not->toContain('.update')
            ->and($slug)->not->toContain('.delete')
            ->and($slug)->not->toContain('.decide')
            ->and($slug)->not->toContain('.activate');
    }

    expect($permissions->has($auditor, 'audit.view'))->toBeTrue();
});
