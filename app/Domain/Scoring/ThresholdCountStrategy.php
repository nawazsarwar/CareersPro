<?php

declare(strict_types=1);

namespace App\Domain\Scoring;

use App\Models\ApplicationSnapshot;
use App\Models\RuleSetVersion;

/**
 * The UGC 2025 draft.
 *
 * It abolishes the Research Score outright and replaces it with a committee
 * determination over nine "notable contribution" areas, of which a candidate
 * must show at least four. There is no total to compute -- the output is a
 * count against a threshold.
 *
 * Authored now and inactive (DR-006). If the draft is notified it loads
 * without a code change, which is the entire reason scoring is polymorphic:
 * a single class built for Table 2 would not have survived it.
 */
final class ThresholdCountStrategy implements ScoringStrategy
{
    public function name(): string
    {
        return 'threshold_count';
    }

    public function score(ApplicationSnapshot $snapshot, RuleSetVersion $version): ScoreResult
    {
        $areas = (array) $version->rule('areas.list');
        $required = (int) $version->rule('areas.minimum');

        /** @var list<array<string, mixed>> $claims */
        $claims = (array) data_get($snapshot->payload, 'claims', []);

        $lines = [];
        $satisfied = [];

        foreach ($areas as $area) {
            $matching = array_filter(
                $claims,
                static fn (array $claim): bool => ($claim['area'] ?? null) === $area
                    && ($claim['evidence_document_id'] ?? null) !== null,
            );

            if ($matching === []) {
                continue;
            }

            $satisfied[] = $area;

            $lines[] = [
                'rule_id' => 'areas.'.$area,
                'citation' => $version->citationFor('areas.'.$area),
                'claim_id' => null,
                'raw_value' => (float) count($matching),
                'apportionment_factor' => 1.0,
                'points' => 1.0,
                'explanation' => __('scoring.area_satisfied', ['area' => $area, 'count' => count($matching)]),
            ];
        }

        // The total is a count, not a score. Nothing downstream may treat it
        // as one, which is why the strategy name travels with the run.
        return new ScoreResult(
            total: (float) count($satisfied),
            lines: $lines,
        );
    }
}
