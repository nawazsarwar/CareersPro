<?php

declare(strict_types=1);

namespace App\Domain\Scoring;

use App\Models\RuleSetVersion;
use Illuminate\Contracts\Container\Container;
use RuntimeException;

/**
 * Selects the strategy from the FROZEN ruleset, never from a runtime flag.
 *
 * An advertisement published under 2018 rules must score under 2018 rules for
 * ever. If the strategy were chosen from a config value or a feature flag,
 * notifying the 2025 draft would silently re-score every open application that
 * had already been made.
 */
final class ResolveStrategy
{
    public function __construct(private readonly Container $container) {}

    public function for(RuleSetVersion $version): ScoringStrategy
    {
        $strategy = $version->rule('strategy');

        /** @var array<string, class-string<ScoringStrategy>> $registry */
        $registry = (array) config('scoring.strategies', []);

        if (! is_string($strategy) || ! array_key_exists($strategy, $registry)) {
            throw new RuntimeException(sprintf(
                'Ruleset version [%s] names no known scoring strategy.',
                $version->version,
            ));
        }

        return $this->container->make($registry[$strategy]);
    }
}
