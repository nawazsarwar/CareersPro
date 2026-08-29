<?php

declare(strict_types=1);

namespace App\Domain\Identity\SecondFactor;

use App\Domain\Identity\TwoFactorPolicy;
use App\Enums\AuthFactor;
use App\Models\User;

/**
 * Which second factor to demand, given what the user has already proved.
 *
 * This is the whole of DR-023's arithmetic, and the reason the challenge had
 * to be ours rather than Fortify's. An OTP counts as the second factor after a
 * password login and never after an OTP login: the channel already used is
 * excluded, because asking for a second code on the same channel proves
 * nothing that the first one did not.
 *
 * The consequence, which is deliberate and is stated in M03's worked example:
 * a user whose only enrolled method is SMS cannot sign in by OTP at all, since
 * there would be no second factor left to ask for. They are offered the
 * password path instead (M03-R21).
 */
final class ResolveRequiredFactor
{
    public function __construct(private readonly TwoFactorPolicy $policy) {}

    public function for(User $user, AuthFactor $used): ?AuthFactor
    {
        if (! $this->policy->requiredFor($user)) {
            return null;
        }

        $available = array_values(array_filter(
            $user->confirmedFactors(),
            fn (AuthFactor $factor): bool => $factor !== $used && $this->policy->permits($user, $factor),
        ));

        if ($available === []) {
            return null;
        }

        // TOTP first where it is held: it is the only factor that does not
        // depend on a network the attacker might also be on.
        if (in_array(AuthFactor::Totp, $available, true)) {
            return AuthFactor::Totp;
        }

        return $available[0];
    }

    /**
     * Whether OTP login is possible at all for this user.
     *
     * Enforcement is not the same question as availability: a user for whom no
     * second factor would remain must be refused *before* a code is sent, not
     * after.
     */
    public function permitsOtpLogin(User $user, AuthFactor $otpFactor): bool
    {
        if (! $this->policy->requiredFor($user)) {
            return true;
        }

        return $this->for($user, $otpFactor) !== null;
    }
}
