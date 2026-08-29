<?php

declare(strict_types=1);

namespace App\Domain\Scoring;

/**
 * @phpstan-type Line array{rule_id: string, citation: string, claim_id: int|null, raw_value: float|null, apportionment_factor: float, points: float, explanation: string}
 */
final readonly class ScoreResult
{
    /**
     * @param  list<Line>  $lines
     */
    public function __construct(
        public float $total,
        public array $lines,
    ) {}
}
