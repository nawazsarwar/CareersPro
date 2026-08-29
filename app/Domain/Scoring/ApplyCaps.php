<?php

declare(strict_types=1);

namespace App\Domain\Scoring;

use App\Models\RuleSetVersion;

/**
 * The 30 per cent cap on categories 5(b) and 6 combined.
 *
 * The Gazette's wording is circular: the cap is "thirty percent of the total
 * research score", and the total includes 5(b) and 6. There is no reading that
 * is simply arithmetic, so the algorithm has to be chosen -- and choosing it
 * is a policy act, which is why the rule carries `pending_ratification` until
 * the Executive Council has decided (DR-013).
 *
 * Where it IS ratified, the solved form is applied: capping against the total
 * of the OTHER categories, which is the only reading that terminates.
 */
final class ApplyCaps
{
    /**
     * @param  array<string, float>  $categoryTotals
     * @return array<string, float>
     */
    public function to(array $categoryTotals, RuleSetVersion $version): array
    {
        $capped = (array) $version->rule('caps.combined.categories');
        $ratio = $version->rule('caps.combined.ratio');

        if ($capped === [] || ! is_numeric($ratio)) {
            return $categoryTotals;
        }

        if ($version->isPendingRatification('caps.combined')) {
            throw new PendingRatificationError(
                'caps.combined',
                $version->citationFor('caps.combined'),
                'The combined cap on the capped categories is defined circularly in the source and has not been ratified.',
            );
        }

        $cappedSum = 0.0;
        $otherSum = 0.0;

        foreach ($categoryTotals as $category => $points) {
            if (in_array($category, $capped, true)) {
                $cappedSum += $points;

                continue;
            }

            $otherSum += $points;
        }

        // The solved form: X ≤ ratio × (X + others) resolves to
        // X ≤ others × ratio / (1 − ratio), which terminates.
        $ceiling = $otherSum * (float) $ratio / (1 - (float) $ratio);

        if ($cappedSum <= $ceiling) {
            return $categoryTotals;
        }

        $scale = $cappedSum > 0.0 ? $ceiling / $cappedSum : 0.0;

        foreach ($categoryTotals as $category => $points) {
            if (in_array($category, $capped, true)) {
                $categoryTotals[$category] = round($points * $scale, 2);
            }
        }

        return $categoryTotals;
    }
}
