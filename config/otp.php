<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| One-Time Codes (DR-023, DR-024)
|--------------------------------------------------------------------------
|
| Codes are stored hashed, bound to a purpose, single-use and rate limited.
| The caps below are enforced in App\Domain\Identity, never in a controller.
|
| Every retry interval derived from these values is stated to the user as a
| time, never as "try again later" -- see M03 §5.
|
*/

return [

    'length' => (int) env('OTP_LENGTH', 6),

    /*
    | How long a code remains valid, in minutes.
    */
    'valid_minutes' => (int) env('OTP_VALID_MINUTES', 10),

    /*
    | How long the user must wait before requesting another code, in minutes.
    */
    'resend_delay_minutes' => (int) env('OTP_DELAY_MINUTES', 3),

    /*
    | The cap per destination per rolling hour. Keyed on the blind index over
    | the delivery target, so the cap holds without decrypting a single row.
    */
    'max_per_hour' => (int) env('AUTH_OTP_MAX_PER_HOUR', 5),

    /*
    | Attempts allowed against a single issued code before it is burned.
    */
    'max_attempts' => (int) env('OTP_MAX_ATTEMPTS', 3),

    /*
    | The gateway App\Domain\Notification\Sms\SendSms resolves. `log` writes
    | to the log and sends nothing, and is the default outside production.
    */
    'default_gateway' => env('SMS_GATEWAY', 'log'),

];
