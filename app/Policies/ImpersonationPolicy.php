<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\RoleSlug;
use App\Models\User;

class ImpersonationPolicy extends ScopedPolicy
{
    public function start(User $user, User $target): bool
    {
        if ($user->is($target)) {
            return false;
        }

        // Never a super administrator: impersonating one is privilege
        // escalation dressed as a support action.
        if ($target->hasRole(RoleSlug::SuperAdmin)) {
            return false;
        }

        return $this->hasPermission($user, 'impersonation.start');
    }
}
