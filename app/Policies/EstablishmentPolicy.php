<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class EstablishmentPolicy extends ScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'master_data.view');
    }

    /**
     * A Dean's-office user reaches this only to read. Sanctioned strength is
     * an Executive Council instrument (CRR Rule 8); a faculty that could edit
     * its own establishment could create its own posts.
     */
    public function update(User $user): bool
    {
        return $this->hasPermission($user, 'master_data.update');
    }
}
