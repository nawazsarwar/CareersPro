<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\AuditLog;
use App\Models\User;

/**
 * Read-only, always (M26 §6).
 *
 * There is deliberately no create, update or delete method here. The absence
 * is the point: an audit log with a mutation policy is an audit log somebody
 * can be persuaded to grant themselves.
 */
class AuditPolicy extends ScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'audit.view');
    }

    public function view(User $user, AuditLog $log): bool
    {
        return $this->hasPermission($user, 'audit.view');
    }

    public function verify(User $user): bool
    {
        return $this->hasPermission($user, 'audit.verify');
    }
}
