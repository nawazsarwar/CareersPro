<?php

declare(strict_types=1);

namespace App\Domain\Payment;

final readonly class VerificationResult
{
    public function __construct(
        public bool $signatureValid,
        public ?string $gatewayTransactionId = null,
        public ?string $reason = null,
    ) {}
}
