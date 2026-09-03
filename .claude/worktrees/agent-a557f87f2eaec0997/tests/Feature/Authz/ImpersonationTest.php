<?php

declare(strict_types=1);

use App\Domain\Access\EndImpersonation;
use App\Domain\Access\StartImpersonation;
use App\Enums\RoleSlug;
use App\Models\AuditLog;
use App\Models\ImpersonationToken;
use App\Models\Role;
use App\Models\TwoFactorMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(Database\Seeders\RolePermissionSeeder::class);
});

function withRole(User $user, RoleSlug $slug): User
{
    $user->roles()->attach(Role::query()->where('slug', $slug->value)->firstOrFail());

    return $user->load('roles');
}

// M25-R08 — the session is replaced and the actor's IP is recorded.

it('invalidates the session and audits the actor IP', function (): void {
    $admin = withRole(User::factory()->staff()->create(), RoleSlug::SuperAdmin);
    $target = withRole(User::factory()->candidate()->create(), RoleSlug::Candidate);

    $this->actingAs($admin);
    $before = session()->getId();

    $token = app(StartImpersonation::class)->handle($admin, $target);
    app(StartImpersonation::class)->consume($token);

    expect(session()->getId())->not->toBe($before);
    $this->assertAuthenticatedAs($target);

    $entry = AuditLog::query()->where('event', 'access.impersonation_started')->firstOrFail();

    expect($entry->actor_id)->toBe((int) $admin->getKey())
        ->and($entry->actor_ip)->not->toBeNull()
        ->and($entry->properties['target_id'])->toBe((int) $target->getKey());
});

// M25-R09 — single use.

it('rejects a token that has already been consumed', function (): void {
    $admin = withRole(User::factory()->staff()->create(), RoleSlug::SuperAdmin);
    $target = withRole(User::factory()->candidate()->create(), RoleSlug::Candidate);

    $this->actingAs($admin);

    $token = app(StartImpersonation::class)->handle($admin, $target);
    app(StartImpersonation::class)->consume($token);

    expect(fn () => app(StartImpersonation::class)->consume($token))
        ->toThrow(RuntimeException::class, 'not usable');
});

it('rejects an expired token', function (): void {
    $admin = withRole(User::factory()->staff()->create(), RoleSlug::SuperAdmin);
    $target = withRole(User::factory()->candidate()->create(), RoleSlug::Candidate);

    $this->actingAs($admin);
    $token = app(StartImpersonation::class)->handle($admin, $target);

    ImpersonationToken::query()->update(['expires_at' => now()->subMinute()]);

    expect(fn () => app(StartImpersonation::class)->consume($token))
        ->toThrow(RuntimeException::class, 'not usable');
});

// M25-R15 — the target's second factor is never consulted.

it('never challenges or reads the target second factor', function (): void {
    $admin = withRole(User::factory()->staff()->create(), RoleSlug::SuperAdmin);
    $target = withRole(User::factory()->candidate()->withTotp()->create(), RoleSlug::Candidate);

    $this->actingAs($admin);

    $token = app(StartImpersonation::class)->handle($admin, $target);
    app(StartImpersonation::class)->consume($token);

    // Straight in, no challenge: the actor cleared their own factor and the
    // target's is not a credential the actor may exercise.
    $this->assertAuthenticatedAs($target);

    expect(TwoFactorMethod::query()->where('user_id', $target->getKey())->value('last_used_at'))
        ->toBeNull();
});

// M26-R10 — an impersonated action names both parties.

it('records both the actor and the impersonated user on later entries', function (): void {
    $admin = withRole(User::factory()->staff()->create(), RoleSlug::SuperAdmin);
    $target = withRole(User::factory()->candidate()->create(), RoleSlug::Candidate);

    $this->actingAs($admin);

    $token = app(StartImpersonation::class)->handle($admin, $target);
    app(StartImpersonation::class)->consume($token);

    // Any audited mutation while impersonating.
    $target->forceFill(['name' => 'Changed While Impersonated'])->save();

    $entry = AuditLog::query()->where('event', 'model.updated')->latest('sequence')->firstOrFail();

    expect($entry->impersonator_id)->toBe((int) $admin->getKey())
        ->and($entry->actor_id)->toBe((int) $target->getKey());
});

it('returns the actor to their own session when it ends', function (): void {
    $admin = withRole(User::factory()->staff()->create(), RoleSlug::SuperAdmin);
    $target = withRole(User::factory()->candidate()->create(), RoleSlug::Candidate);

    $this->actingAs($admin);

    $token = app(StartImpersonation::class)->handle($admin, $target);
    app(StartImpersonation::class)->consume($token);

    app(EndImpersonation::class)->handle();

    $this->assertAuthenticatedAs($admin);

    expect(AuditLog::query()->where('event', 'access.impersonation_ended')->exists())->toBeTrue()
        ->and(ImpersonationToken::query()->whereNotNull('ended_at')->count())->toBe(1);
});

it('refuses to impersonate a super administrator', function (): void {
    $admin = withRole(User::factory()->staff()->create(), RoleSlug::SuperAdmin);
    $other = withRole(User::factory()->staff()->create(), RoleSlug::SuperAdmin);

    expect(fn () => app(StartImpersonation::class)->handle($admin, $other))
        ->toThrow(RuntimeException::class, 'super administrator');

    expect($admin->can('start', $other))->toBeFalse();
});

it('refuses to impersonate oneself', function (): void {
    $admin = withRole(User::factory()->staff()->create(), RoleSlug::SuperAdmin);

    expect(fn () => app(StartImpersonation::class)->handle($admin, $admin))
        ->toThrow(RuntimeException::class, 'themselves');
});

it('leaves the chain intact across a whole impersonation cycle', function (): void {
    $admin = withRole(User::factory()->staff()->create(), RoleSlug::SuperAdmin);
    $target = withRole(User::factory()->candidate()->create(), RoleSlug::Candidate);

    $this->actingAs($admin);

    $token = app(StartImpersonation::class)->handle($admin, $target);
    app(StartImpersonation::class)->consume($token);
    app(EndImpersonation::class)->handle();

    expect(app(App\Domain\Audit\VerifyAuditChain::class)->handle()->intact)->toBeTrue();
});
