<?php

declare(strict_types=1);

namespace App\Domain\Examination;

final readonly class AllocationReport
{
    /**
     * @param  list<array{application_id: int, centre_id: int, rule: string}>  $placed
     * @param  list<array{application_id: int, reason: string}>  $unplaced
     */
    public function __construct(
        public array $placed,
        public array $unplaced,
    ) {}

    public function placedCount(): int
    {
        return count($this->placed);
    }

    public function unplacedCount(): int
    {
        return count($this->unplaced);
    }
}
