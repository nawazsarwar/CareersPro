<?php

declare(strict_types=1);

namespace App\Domain\Notification\Sms\Gateways;

use App\Domain\Notification\Sms\SmsGateway;
use App\Domain\Notification\Sms\SmsResult;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Sends nothing. The default outside production and the binding used by the
 * test suite.
 *
 * The body is logged with the code masked: a development log that contains
 * live one-time codes is a development log that must not be shipped anywhere.
 */
final class LogSmsGateway implements SmsGateway
{
    public function send(string $mobile, string $body): SmsResult
    {
        Log::info('SMS (not sent — log gateway)', [
            'mobile' => '•••••• '.mb_substr($mobile, -4),
            'body' => preg_replace('/\b\d{4,8}\b/', '[code]', $body),
        ]);

        return new SmsResult(accepted: true, providerReference: 'log-'.Str::uuid()->toString());
    }
}
