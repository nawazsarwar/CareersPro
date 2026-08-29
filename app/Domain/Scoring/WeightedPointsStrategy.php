<?php

declare(strict_types=1);

namespace App\Domain\Scoring;

use App\Models\ApplicationSnapshot;
use App\Models\RuleSetVersion;

/**
 * UGC Regulations 2018, Appendix II Table 2 — the Research Score.
 *
 * Six categories, thirty-three scored sub-rows, two faculty columns, six
 * impact-factor bands and two apportionment rules. Every value read from the
 * frozen ruleset; none of them written here.
 */
final class WeightedPointsStrategy implements ScoringStrategy
{
    public function __construct(
        private readonly Apportion $apportion,
        private readonly ApplyCaps $caps,
        private readonly AssertCategoryFloor $floor,
    ) {}

    public function name(): string
    {
        return 'weighted_points';
    }

    public function score(ApplicationSnapshot $snapshot, RuleSetVersion $version): ScoreResult
    {
        $lines = [];
        $categoryTotals = [];

        /** @var list<array<string, mixed>> $claims */
        $claims = (array) data_get($snapshot->payload, 'claims', []);

        $column = $this->facultyColumn($snapshot, $version);

        foreach ($claims as $claim) {
            $line = $this->scoreClaim($claim, $version, $column);

            if ($line === null) {
                continue;
            }

            $lines[] = $line;

            $category = (string) ($claim['category'] ?? 'unknown');
            $categoryTotals[$category] = ($categoryTotals[$category] ?? 0.0) + $line['points'];
        }

        $this->floor->check($categoryTotals, $version);
        $categoryTotals = $this->caps->to($categoryTotals, $version);

        return new ScoreResult(
            total: round(array_sum($categoryTotals), 2),
            lines: $lines,
        );
    }

    /**
     * Column I scores 8 points a paper, Column II scores 10.
     *
     * DR-014 puts Librarian and Physical Education cadres in Column II. The
     * previous rules file set a flat base of 8, which would have understated
     * every Column II candidate by a fifth.
     */
    private function facultyColumn(ApplicationSnapshot $snapshot, RuleSetVersion $version): string
    {
        $faculty = (string) data_get($snapshot->payload, 'candidate.faculty_column', 'I');

        return in_array($faculty, ['I', 'II'], true) ? $faculty : 'I';
    }

    /**
     * @param  array<string, mixed>  $claim
     * @return array{rule_id: string, citation: string, claim_id: int|null, raw_value: float|null, apportionment_factor: float, points: float, explanation: string}|null
     */
    private function scoreClaim(array $claim, RuleSetVersion $version, string $column): ?array
    {
        $category = (string) ($claim['category'] ?? '');

        if ($category === '') {
            return null;
        }

        /*
         * Table 2's header enumerates the evidence each claim must carry.
         * A claim without it contributes zero -- recorded as a line with an
         * explanation rather than dropped, so the candidate can see why.
         */
        if (($claim['evidence_document_id'] ?? null) === null) {
            return [
                'rule_id' => 'evidence.required',
                'citation' => $version->citationFor('evidence.required'),
                'claim_id' => isset($claim['id']) ? (int) $claim['id'] : null,
                'raw_value' => null,
                'apportionment_factor' => 0.0,
                'points' => 0.0,
                'explanation' => __('scoring.no_evidence'),
            ];
        }

        $rulePath = 'categories.'.$category;
        $base = $version->rule($rulePath.'.points.column_'.$column);

        if (! is_numeric($base)) {
            return null;
        }

        $points = (float) $base;
        $explanation = __('scoring.base_points', ['points' => $points, 'column' => $column]);

        // The impact-factor augmentation. Whether these REPLACE or are ADDED
        // to the base is not stated in the source, and for a Professor
        // applicant with twenty papers the difference is 160 to 200 points
        // against a threshold of 120. The engine refuses rather than choosing.
        if (isset($claim['impact_factor']) && is_numeric($claim['impact_factor'])) {
            $points = $this->augment($points, (float) $claim['impact_factor'], $version, $explanation);
        }

        $factor = $this->apportion->for($claim, $version);

        return [
            'rule_id' => $rulePath,
            'citation' => $version->citationFor($rulePath),
            'claim_id' => isset($claim['id']) ? (int) $claim['id'] : null,
            'raw_value' => $points,
            'apportionment_factor' => $factor,
            'points' => round($points * $factor, 2),
            'explanation' => $explanation.' '.__('scoring.apportioned', ['factor' => $factor]),
        ];
    }

    private function augment(float $base, float $impactFactor, RuleSetVersion $version, string &$explanation): float
    {
        if ($version->isPendingRatification('impact_factor')) {
            throw new PendingRatificationError(
                'impact_factor',
                $version->citationFor('impact_factor'),
                'The source says the score "would be augmented" by the impact-factor values without saying whether they replace or are added to the base, and the difference is determinative.',
            );
        }

        $band = $this->band($impactFactor, $version);

        if ($band === null) {
            return $base;
        }

        $mode = (string) $version->rule('impact_factor.mode');
        $explanation .= ' '.__('scoring.impact_band', ['value' => $band]);

        return $mode === 'replace' ? $band : $base + $band;
    }

    private function band(float $impactFactor, RuleSetVersion $version): ?float
    {
        /** @var list<array{min: float|null, max: float|null, points: float}> $bands */
        $bands = (array) $version->rule('impact_factor.bands');

        foreach ($bands as $band) {
            $min = $band['min'] ?? null;
            $max = $band['max'] ?? null;

            if (($min === null || $impactFactor >= (float) $min)
                && ($max === null || $impactFactor < (float) $max)) {
                return (float) $band['points'];
            }
        }

        return null;
    }
}
