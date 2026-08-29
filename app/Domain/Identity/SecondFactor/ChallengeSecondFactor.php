<?php

declare(strict_types=1);

namespace App\Domain\Identity\SecondFactor;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Domain\Identity\IssueOtp;
use App\Domain\Identity\OtpIssueResult;
use App\Enums\AuditEventName;
use App\Enums\AuthFactor;
use App\Enums\OtpPurpose;
use App\Models\User;
use Illuminate\Support\Facades\Request;

/**
 * Issues whatever the demanded factor needs.
 *
 * TOTP needs nothing sent -- the authenticator already holds the secret. SMS
 * and email need a code, and it is issued under the `two_factor` purpose so it
 * can never be replayed against the login form.
 */
final class ChallengeSecondFactor
{
    public function __construct(
        private readonly IssueOtp $issueOtp,
        private readonly RecordAuditEvent $audit,
    ) {}

    public function handle(User $user, AuthFactor $factor): ?OtpIssueResult
    {
        $this->audit->handle(new AuditEvent(
            event: AuditEventName::SecondFactorChallenged,
            properties: ['factor' => $factor->value],
            subject: $user,
            actorId: (int) $user->getKey(),
            actorIp: Request::ip(),
        ));

        $channel = $factor->otpChannel();

        if ($channel === null) {
            return null;
        }

        return $this->issueOtp->handle($user, OtpPurpose::TwoFactor, $channel);
    }
}
