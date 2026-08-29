<?php

declare(strict_types=1);

namespace App\Domain\Identity;

use Carbon\CarbonImmutable;

/**
 * Why an OTP was or was not issued.
 *
 * The reason is an enum-like string rather than a message, because the message
 * shown depends on where the user is: on the pending-login screen the reason is
 * stated in full, and on the first submit nothing is said at all, or the
 * response would enumerate accounts (M03 §3).
 */
final readonly class OtpIssueResult
{
    public const SENT = 'sent';

    public const NO_MOBILE = 'no_mobile';

    public const UNVERIFIED_MOBILE = 'unverified_mobile';

    public const COOLDOWN = 'cooldown';

    public const HOURLY_CAP = 'hourly_cap';

    public const GATEWAY_FAILED = 'gateway_failed';

    public function __construct(
        public string $reason,
        public ?CarbonImmutable $retryAt = null,
        public ?string $maskedDestination = null,
    ) {}

    public function wasSent(): bool
    {
        return $this->reason === self::SENT;
    }
}
