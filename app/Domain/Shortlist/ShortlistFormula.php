<?php

declare(strict_types=1);

namespace App\Domain\Shortlist;

use InvalidArgumentException;

/**
 * How many candidates are called for how many posts (DR-019).
 *
 * Five for the first post, three for each one after: 5 + 3 × (vacancies − 1).
 * Configurable, because UGC 2018 Table 3A Note B leaves the ratio to the
 * university -- but bounded, because a formula that called everybody would
 * make shortlisting meaningless and one that called nobody would make it
 * unlawful.
 */
final class ShortlistFormula
{
    public function __construct(
        private readonly int $base = 5,
        private readonly int $increment = 3,
    ) {}

    public function for(int $vacancies): int
    {
        if ($vacancies < 1) {
            throw new InvalidArgumentException('A post with no vacancies cannot be shortlisted for.');
        }

        return $this->base + $this->increment * ($vacancies - 1);
    }

    /**
     * The ceiling a configured formula may not exceed.
     *
     * Five times the vacancies: beyond that the shortlist stops being a
     * shortlist, and a Selection Committee that must interview forty people
     * for one post on the day of the meeting (cl. 5.1 VIII(c)) cannot do it
     * properly.
     */
    public function assertWithinCeiling(int $vacancies, int $called): void
    {
        $ceiling = 5 * $vacancies;

        if ($called > $ceiling) {
            throw new InvalidArgumentException(sprintf(
                'Calling %d candidates for %d vacancies exceeds the ceiling of %d.',
                $called,
                $vacancies,
                $ceiling,
            ));
        }
    }
}
