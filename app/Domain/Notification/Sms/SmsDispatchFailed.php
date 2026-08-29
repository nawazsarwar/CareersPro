<?php

declare(strict_types=1);

namespace App\Domain\Notification\Sms;

use RuntimeException;

/**
 * A failed dispatch fails closed (M03 §3): no session, no partial login, no
 * deferred verification. The caller returns the user to the password path and
 * audits `auth.otp.failed`.
 */
final class SmsDispatchFailed extends RuntimeException {}
