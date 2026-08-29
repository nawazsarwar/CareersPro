<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Access\ResolvePermissions;
use App\Models\Advertisement;
use App\Models\AuditLog;
use App\Models\Designation;
use App\Models\OrganisationalUnit;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use App\Policies\AdvertisementPolicy;
use App\Policies\AuditPolicy;
use App\Policies\DesignationPolicy;
use App\Policies\EstablishmentPolicy;
use App\Policies\ImpersonationPolicy;
use App\Policies\PostPolicy;
use App\Policies\ProfilePolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AccessServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::policy(Advertisement::class, AdvertisementPolicy::class);
        Gate::policy(AuditLog::class, AuditPolicy::class);
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(Designation::class, DesignationPolicy::class);
        Gate::policy(OrganisationalUnit::class, EstablishmentPolicy::class);
        Gate::policy(Profile::class, ProfilePolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        // Impersonation is an action on a user, but a different question from
        // UserPolicy's, so it is defined rather than folded in: "may I edit
        // this account" and "may I become it" must not share an answer.
        Gate::define('start', [ImpersonationPolicy::class, 'start']);

        // A role or permission change must not wait fifteen minutes to take
        // effect: revoking access that stays live for a quarter of an hour is
        // not revoking access (M25-R10).
        Role::saved(static fn (Role $role) => self::invalidateFor($role));
        Role::deleted(static fn (Role $role) => self::invalidateFor($role));
    }

    private static function invalidateFor(Role $role): void
    {
        $role->users()->each(static fn (User $user) => ResolvePermissions::invalidate($user));
    }
}
