<?php

declare(strict_types=1);

namespace App\Domain\Identity;

use App\Enums\LoginChannel;
use App\Models\User;

/**
 * Which channel the sign-in card offers first (DR-023).
 *
 * A per-user preference wins; absent one, the default for the user's class in
 * config/auth_channels.php applies. Storing the resolved value on the row
 * instead would freeze today's default into every account created before the
 * administrator changed it.
 *
 * This decides what is *offered*, never what is *permitted*: both channels
 * remain reachable for every user.
 */
final class ResolveLoginChannel
{
    public function for(User $user): LoginChannel
    {
        if ($user->preferred_login_channel !== null) {
            return $user->preferred_login_channel;
        }

        $configured = config("auth_channels.classes.{$user->userClass()}.default_login_channel");

        return LoginChannel::tryFrom(is_string($configured) ? $configured : '') ?? LoginChannel::Password;
    }

    public function requiresVerifiedMobile(User $user): bool
    {
        return (bool) config("auth_channels.classes.{$user->userClass()}.require_verified_mobile", false);
    }
}
