<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Profile;
use App\Models\User;

/**
 * M03-R07, M25-R01 — ownership only.
 *
 * Staff read a candidate's data through ApplicationPolicy, never through this
 * one: a policy that grants staff access to profiles grants it to all of them,
 * including the ones with no application to the post in question.
 */
class ProfilePolicy extends ScopedPolicy
{
    public function view(User $user, Profile $profile): bool
    {
        return $this->permits($user, 'profile.view', $profile);
    }

    public function update(User $user, Profile $profile): bool
    {
        return $this->permits($user, 'profile.update', $profile);
    }

    public function export(User $user, Profile $profile): bool
    {
        return $this->permits($user, 'profile.export', $profile);
    }
}
