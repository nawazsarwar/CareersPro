<?php

declare(strict_types=1);

namespace App\Domain\Identity;

use App\Enums\AuthFactor;
use App\Models\User;

/**
 * Whether a second factor is required of this user, and which methods they may
 * hold (M03-R22, M03-R25).
 */
final class TwoFactorPolicy
{
    public function requiredFor(User $user): bool
    {
        return (bool) config("auth_channels.classes.{$user->userClass()}.second_factor_required", false);
    }

    /**
     * Email is refused as a second factor for staff.
     *
     * A staff account's recovery address is frequently the same institutional
     * mailbox the session itself is reached from, so an emailed code is a
     * second copy of the first factor rather than a second factor.
     */
    public function permits(User $user, AuthFactor $factor): bool
    {
        if (! $factor->isSecondFactorCandidate()) {
            return false;
        }

        if ($factor === AuthFactor::Email && $user->isStaff()) {
            return false;
        }

        return in_array($factor->value, (array) config('auth_channels.second_factor_channels', []), true);
    }

    /**
     * M03-R23 — the last method may be removed only where 2FA is not enforced.
     */
    public function mayRemove(User $user, AuthFactor $factor): bool
    {
        if (! $this->requiredFor($user)) {
            return true;
        }

        $remaining = array_filter(
            $user->confirmedFactors(),
            static fn (AuthFactor $held): bool => $held !== $factor,
        );

        return $remaining !== [];
    }
}
