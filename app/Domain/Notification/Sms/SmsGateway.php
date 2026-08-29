<?php

declare(strict_types=1);

namespace App\Domain\Notification\Sms;

/**
 * The only thing the domain knows about SMS (DR-024).
 *
 * No provider name appears outside App\Domain\Notification\Sms\Gateways, and
 * an architecture test asserts it.
 */
interface SmsGateway
{
    /**
     * @throws SmsDispatchFailed
     */
    public function send(string $mobile, string $body): SmsResult;
}
