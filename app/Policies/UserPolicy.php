<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\RoleSlug;
use App\Models\User;

class UserPolicy extends ScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'user.view');
    }

    public function view(User $user, User $target): bool
    {
        return $user->is($target) || $this->hasPermission($user, 'user.view');
    }

    public function update(User $user, User $target): bool
    {
        return $user->is($target) || $this->hasPermission($user, 'user.update');
    }

    public function assignRole(User $user, User $target): bool
    {
        // Assigning roles is how privilege is granted, so it is not delegated
        // below super_admin in v1.
        return $user->hasRole(RoleSlug::SuperAdmin) && ! $user->is($target);
    }
}
