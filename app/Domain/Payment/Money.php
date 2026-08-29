<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use InvalidArgumentException;

/**
 * Paise, always, as an integer.
 *
 * Floats cannot represent 0.1 exactly, and a fee schedule summed in floats
 * drifts. Money that is out by a paisa over 78,232 applications is money
 * somebody has to reconcile by hand.
 */
final readonly class Money
{
    public function __construct(public int $paise)
    {
        if ($paise < 0) {
            throw new InvalidArgumentException('A fee cannot be negative.');
        }
    }

    public static function rupees(int|float $rupees): self
    {
        return new self((int) round($rupees * 100));
    }

    public function isZero(): bool
    {
        return $this->paise === 0;
    }

    public function format(): string
    {
        return '₹'.number_format($this->paise / 100, 2);
    }
}
