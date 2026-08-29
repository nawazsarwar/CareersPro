<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Designation;
use App\Models\User;

/**
 * University-wide. Read for all staff, mutation for recruitment_admin and
 * super_admin only (M35 §6).
 *
 * Dean's-office users read but never write: sanctioned strength is vested in
 * the Executive Council by CRR Rule 8, not in a faculty.
 */
class DesignationPolicy extends ScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'master_data.view');
    }

    public function view(User $user, Designation $designation): bool
    {
        return $this->hasPermission($user, 'master_data.view');
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'master_data.create');
    }

    public function update(User $user, Designation $designation): bool
    {
        return $this->hasPermission($user, 'master_data.update');
    }
}
