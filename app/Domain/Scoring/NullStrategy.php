<?php

declare(strict_types=1);

namespace App\Domain\Scoring;

use App\Models\ApplicationSnapshot;
use App\Models\RuleSetVersion;

/**
 * Cadres with no scoring at all.
 *
 * An explicit strategy rather than a null check at every call site: "this post
 * is not scored" is a real answer, and a caller that has to remember to ask
 * first is a caller that will eventually forget.
 */
final class NullStrategy implements ScoringStrategy
{
    public function name(): string
    {
        return 'null_strategy';
    }

    public function score(ApplicationSnapshot $snapshot, RuleSetVersion $version): ScoreResult
    {
        return new ScoreResult(total: 0.0, lines: []);
    }
}
