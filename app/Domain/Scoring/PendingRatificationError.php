<?php

declare(strict_types=1);

namespace App\Domain\Scoring;

use RuntimeException;

/**
 * Invariant I5: refuse, never guess.
 *
 * Six things in UGC 2018 Appendix II Table 2 are genuinely ambiguous, and the
 * AMU Ordinances reproduce the wording verbatim without interpreting any of
 * them (DR-013). Whether the impact-factor values REPLACE or are ADDED to the
 * base per-paper score is worth 160 to 200 points to a Professor applicant
 * with twenty papers, against a threshold of 120.
 *
 * A scoring engine that picks a reading is a scoring engine that has quietly
 * made University policy. This exception is what makes it stop and say so
 * instead: the run is recorded as blocked, naming the rule, and the Executive
 * Council decides.
 */
final class PendingRatificationError extends RuntimeException
{
    public function __construct(
        public readonly string $ruleId,
        public readonly string $citation,
        string $message,
    ) {
        parent::__construct($message);
    }
}
