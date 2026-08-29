<?php

declare(strict_types=1);

namespace App\Domain\Identity;

use App\Domain\Identity\SecondFactor\ResolveRequiredFactor;
use App\Enums\AuthFactor;
use App\Enums\OtpChannel;
use App\Enums\OtpPurpose;
use App\Models\User;

/**
 * Begins a passwordless sign-in (DR-023).
 *
 * The response to *Send me a code instead* is identical for an unknown
 * identifier, a known one with a verified mobile and a known one without: the
 * caller always lands on the pending-login screen, and only there is the
 * reason stated. Anything else turns this form into an account-enumeration
 * oracle, which is exactly what a public recruitment portal must not offer.
 */
final class StartOtpLogin
{
    public function __construct(
        private readonly CredentialResolver $resolver,
        private readonly IssueOtp $issueOtp,
        private readonly ResolveRequiredFactor $requiredFactor,
        private readonly PendingLogin $pending,
    ) {}

    public function handle(string $login): OtpIssueResult
    {
        $user = User::query()
            ->where($this->resolver->resolve($login), $login)
            ->with('profile')
            ->first();

        if ($user === null || ! $user->status->canSignIn()) {
            // No code, no record, no timing difference worth measuring, and
            // the same screen as every other outcome.
            return new OtpIssueResult(OtpIssueResult::NO_MOBILE);
        }

        $profile = $user->profile;

        if ($profile?->mobile === null) {
            return new OtpIssueResult(OtpIssueResult::NO_MOBILE);
        }

        if (! $profile->hasVerifiedMobile()) {
            return new OtpIssueResult(OtpIssueResult::UNVERIFIED_MOBILE);
        }

        // Refused before a code is sent, not after: a user for whom no second
        // factor would remain must not be walked halfway through a flow that
        // cannot complete (M03-R21).
        if (! $this->requiredFactor->permitsOtpLogin($user, AuthFactor::Sms)) {
            return new OtpIssueResult(OtpIssueResult::UNVERIFIED_MOBILE);
        }

        $result = $this->issueOtp->handle($user, OtpPurpose::Login, OtpChannel::Sms);

        if ($result->wasSent()) {
            $this->pending->start($user, AuthFactor::Sms);
        }

        return $result;
    }
}
