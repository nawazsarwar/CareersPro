<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\RoleSlug;
use App\Models\Role;
use App\Models\User;

/**
 * University-wide, super_admin only for mutation (M25 §6).
 */
class RolePolicy extends ScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'role.view');
    }

    public function view(User $user, Role $role): bool
    {
        return $this->hasPermission($user, 'role.view');
    }

    public function create(User $user): bool
    {
        return $user->hasRole(RoleSlug::SuperAdmin);
    }

    public function update(User $user, Role $role): bool
    {
        // A system role's slug is named by the policies. Renaming one through
        // the UI would silently disable the checks that reference it.
        return $user->hasRole(RoleSlug::SuperAdmin) && ! $role->is_system;
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasRole(RoleSlug::SuperAdmin) && ! $role->is_system;
    }
}
