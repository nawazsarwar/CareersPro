<?php

declare(strict_types=1);

namespace App\Domain\Audit;

/**
 * The result of verifying a range of the chain.
 *
 * A broken chain is a P1 security incident, not a warning, so the report names
 * the first divergent sequence rather than a count of problems: the first break
 * is where the investigation starts.
 */
final readonly class ChainReport
{
    public function __construct(
        public bool $intact,
        public int $from,
        public int $to,
        public int $verified,
        public ?int $brokenAt = null,
        public ?string $reason = null,
    ) {}

    public function summary(): string
    {
        if ($this->intact) {
            return sprintf('Chain verified, sequences %s–%s.', number_format($this->from), number_format($this->to));
        }

        return sprintf('Chain broken at sequence %s. %s', number_format((int) $this->brokenAt), (string) $this->reason);
    }
}
