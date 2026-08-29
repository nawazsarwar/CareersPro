<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * `DoublePayment` is a first-class status, not an exception path (M08 §3).
 *
 * It happens -- CU-Chayan's users report it at deadline hours, and the legacy
 * portal's 45,280 orders against 0 transaction rows say the same. A status the
 * system can name is a status finance can filter, count and refund; an
 * exception path is a support ticket.
 */
enum OrderStatus: string
{
    case Created = 'created';
    case Initiated = 'initiated';
    case Paid = 'paid';
    case Failed = 'failed';
    case Cancelled = 'cancelled';
    case DoublePayment = 'double_payment';
    case Refunded = 'refunded';

    public function isSettled(): bool
    {
        return in_array($this, [self::Paid, self::DoublePayment, self::Refunded], true);
    }

    /**
     * Whether this status entitles the candidate to proceed.
     *
     * A double payment does: they paid, twice, and the second one is finance's
     * problem to refund, not a reason to hold up the application.
     */
    public function grantsAccess(): bool
    {
        return in_array($this, [self::Paid, self::DoublePayment], true);
    }
}
