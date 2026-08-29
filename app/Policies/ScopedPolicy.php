<?php

declare(strict_types=1);

namespace App\Policies;

use App\Domain\Access\ResolvePermissions;
use App\Domain\Access\ResolveScopes;
use App\Enums\RoleSlug;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * The base every scoped resource policy must extend (M25 §3, M25-R04).
 *
 * The rule it exists to make unforgettable: **a permission alone never
 * authorises a row.** Both halves are evaluated together in permits(), so the
 * scope check cannot be omitted by writing a policy method that only asks
 * about the permission -- which is precisely the shape of the defect this
 * module closes, where the seeder granted every candidate `profile_edit` on
 * every row.
 */
abstract class ScopedPolicy
{
    public function __construct(
        protected readonly ResolvePermissions $permissions,
        protected readonly ResolveScopes $scopes,
    ) {}

    /**
     * Both halves, always.
     */
    protected function permits(User $user, string $ability, Model $model): bool
    {
        return $this->hasPermission($user, $ability) && $this->inScope($user, $model);
    }

    protected function hasPermission(User $user, string $ability): bool
    {
        // super_admin is a named-role check, never roles()->where('id', 1):
        // an id is a database accident and reordering a seeder would reassign
        // the keys to the system.
        if ($user->hasRole(RoleSlug::SuperAdmin)) {
            return true;
        }

        return $this->permissions->has($user, $ability);
    }

    /**
     * Ownership first, then organisational unit. A model that declares
     * neither is not a scoped resource and does not belong behind this policy.
     */
    protected function inScope(User $user, Model $model): bool
    {
        if ($this->ownedBy($user, $model)) {
            return true;
        }

        $paths = $this->scopes->for($user);

        if ($paths === null) {
            return true;                 // university-wide
        }

        if ($paths === []) {
            return false;                // no role, therefore no rows
        }

        $modelPath = $this->pathOf($model);

        if ($modelPath === null) {
            // A scoped user against a row with no organisational unit: this is
            // General recruitment, which DR-010 administers centrally. A
            // Dean's-office user reaches none of it (M25-R03).
            return false;
        }

        foreach ($paths as $path) {
            if (str_starts_with($modelPath, $path)) {
                return true;
            }
        }

        return false;
    }

    protected function ownedBy(User $user, Model $model): bool
    {
        return $model->getAttribute('user_id') !== null
            && (int) $model->getAttribute('user_id') === (int) $user->getKey();
    }

    /**
     * The unit path a row belongs to.
     *
     * Read from the row's own snapshot where it has one, so a department
     * renamed or re-parented in 2028 cannot silently move a 2026 record out of
     * the scope that decided it.
     */
    protected function pathOf(Model $model): ?string
    {
        $snapshot = $model->getAttribute('ou_path_snapshot');

        if (is_string($snapshot) && $snapshot !== '') {
            return $snapshot;
        }

        $unitId = $model->getAttribute('organisational_unit_id');

        if ($unitId === null) {
            return null;
        }

        $path = \App\Models\OrganisationalUnit::query()->whereKey($unitId)->value('path');

        return is_string($path) ? $path : null;
    }
}
