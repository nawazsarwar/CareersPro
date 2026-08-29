<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\Enums\OrderStatus;

/**
 * The common shape every gateway's MIS file is mapped into.
 *
 * Each provider publishes different columns in a different order with its own
 * status vocabulary. Reconciliation reads this shape and nothing else.
 */
final readonly class ReconciliationRow
{
    public function __construct(
        public string $orderUid,
        public ?string $gatewayTransactionId,
        public OrderStatus $status,
        public int $amountPaise,
    ) {}
}
