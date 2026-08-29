<?php

declare(strict_types=1);

namespace App\Domain\Scoring;

use App\Models\RuleSetVersion;

/**
 * "The research score shall be from the minimum of three categories out of
 * six" (UGC 2018 Appendix II Table 2).
 *
 * The source does not say what happens when a candidate scores in only two:
 * are they ineligible, or is the excess disregarded? Those give different
 * outcomes for the same dossier, so the rule carries `pending_ratification`
 * and the engine refuses rather than choosing (DR-013).
 */
final class AssertCategoryFloor
{
    /**
     * @param  array<string, float>  $categoryTotals
     */
    public function check(array $categoryTotals, RuleSetVersion $version): void
    {
        $minimum = $version->rule('floors.category_minimum.count');

        if (! is_numeric($minimum)) {
            return;
        }

        $scored = count(array_filter($categoryTotals, static fn (float $points): bool => $points > 0.0));

        if ($scored >= (int) $minimum) {
            return;
        }

        if ($version->isPendingRatification('floors.category_minimum')) {
            throw new PendingRatificationError(
                'floors.category_minimum',
                $version->citationFor('floors.category_minimum'),
                sprintf(
                    'The dossier scores in %d categories against a stated minimum of %d, and the source does not say whether that is a disqualification or a disregard.',
                    $scored,
                    (int) $minimum,
                ),
            );
        }
    }
}
