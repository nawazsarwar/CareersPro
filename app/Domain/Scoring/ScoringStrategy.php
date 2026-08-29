<?php

declare(strict_types=1);

namespace App\Domain\Scoring;

use App\Models\ApplicationSnapshot;
use App\Models\RuleSetVersion;

/**
 * Polymorphic per ruleset, and the 2025 draft is why.
 *
 * UGC 2018 scores structured publication metadata into a Research Score. The
 * 2025 draft abolishes the Research Score entirely and asks a committee to
 * judge narrative contributions in at least four of nine areas. A single
 * scoring class built for Table 2 would not survive that notification -- and
 * DR-006 requires the 2025 ruleset to load without a code change if it is
 * notified.
 */
interface ScoringStrategy
{
    public function name(): string;

    /**
     * @throws PendingRatificationError where a rule the score depends on is
     *                                  unratified (I5)
     */
    public function score(ApplicationSnapshot $snapshot, RuleSetVersion $version): ScoreResult;
}
