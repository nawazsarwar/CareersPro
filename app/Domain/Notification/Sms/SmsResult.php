<?php

declare(strict_types=1);

namespace App\Domain\Notification\Sms;

final readonly class SmsResult
{
    public function __construct(
        public bool $accepted,
        public ?string $providerReference = null,
    ) {}
}
