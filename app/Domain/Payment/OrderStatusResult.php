<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\Enums\OrderStatus;

final readonly class OrderStatusResult
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public OrderStatus $status,
        public ?string $gatewayTransactionId = null,
        public ?int $amountPaise = null,
        public ?string $method = null,
        public array $raw = [],
    ) {}
}
