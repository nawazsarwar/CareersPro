<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Scoring strategies
|--------------------------------------------------------------------------
|
| Registered here rather than in the domain so that a ruleset names its
| strategy as data. A ruleset frozen onto an advertisement therefore carries
| its own scoring behaviour, and notifying a new regulation cannot re-score
| applications already made under the old one.
|
*/

return [

    'strategies' => [
        'weighted_points' => App\Domain\Scoring\WeightedPointsStrategy::class,
        'threshold_count' => App\Domain\Scoring\ThresholdCountStrategy::class,
        'non_teaching_test' => App\Domain\Scoring\NonTeachingTestStrategy::class,
        'null_strategy' => App\Domain\Scoring\NullStrategy::class,
    ],

];
