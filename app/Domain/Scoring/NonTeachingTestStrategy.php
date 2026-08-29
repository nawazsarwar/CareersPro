<?php

declare(strict_types=1);

namespace App\Domain\Scoring;

use App\Models\ApplicationSnapshot;
use App\Models\RuleSetVersion;

/**
 * UGC Model CRR 2022 — Paper I, Paper II, skill test and interview.
 *
 * Structurally different from the teaching regime, and the difference is
 * statutory rather than stylistic: here the written papers ARE additive with
 * the interview at a 20 per cent weighting, where for teaching the screening
 * score must never enter the merit list at all.
 *
 * The skill test is qualifying only and never additive: 50 marks with a
 * minimum of 25, and passing it admits a candidate to the interview without
 * contributing to their rank.
 */
final class NonTeachingTestStrategy implements ScoringStrategy
{
    public function name(): string
    {
        return 'non_teaching_test';
    }

    public function score(ApplicationSnapshot $snapshot, RuleSetVersion $version): ScoreResult
    {
        $lines = [];
        $total = 0.0;

        foreach (['paper_one', 'paper_two', 'interview'] as $component) {
            $mark = data_get($snapshot->payload, 'marks.'.$component);

            if (! is_numeric($mark)) {
                continue;
            }

            $weight = $version->rule('components.'.$component.'.weight');
            $weight = is_numeric($weight) ? (float) $weight : 1.0;

            $points = round((float) $mark * $weight, 2);
            $total += $points;

            $lines[] = [
                'rule_id' => 'components.'.$component,
                'citation' => $version->citationFor('components.'.$component),
                'claim_id' => null,
                'raw_value' => (float) $mark,
                'apportionment_factor' => $weight,
                'points' => $points,
                'explanation' => __('scoring.component', ['component' => $component, 'weight' => $weight]),
            ];
        }

        $skill = data_get($snapshot->payload, 'marks.skill_test');

        if (is_numeric($skill)) {
            $minimum = (float) $version->rule('components.skill_test.minimum');

            // Qualifying only. Recorded as a line with zero points so the
            // report shows it was taken and passed without implying it
            // contributed to the rank.
            $lines[] = [
                'rule_id' => 'components.skill_test',
                'citation' => $version->citationFor('components.skill_test'),
                'claim_id' => null,
                'raw_value' => (float) $skill,
                'apportionment_factor' => 0.0,
                'points' => 0.0,
                'explanation' => (float) $skill >= $minimum
                    ? __('scoring.skill_passed', ['minimum' => $minimum])
                    : __('scoring.skill_failed', ['minimum' => $minimum]),
            ];
        }

        return new ScoreResult(total: round($total, 2), lines: $lines);
    }
}
