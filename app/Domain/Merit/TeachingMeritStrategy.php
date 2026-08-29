<?php

declare(strict_types=1);

namespace App\Domain\Merit;

/**
 * Teaching merit is the interview alone.
 *
 * UGC 2018 cl. 4.1 I Note and cl. 5.3: the shortlisting score under Tables 3A
 * and 3B decides who is CALLED, and takes no part in deciding who is
 * SELECTED. The two are different questions and the regulations keep them
 * apart.
 *
 * The guard throws rather than ignoring an unexpected input, because silently
 * dropping a shortlisting score would produce a list that looked right and was
 * computed wrongly -- and nobody would find out until a candidate challenged
 * the appointment.
 */
final class TeachingMeritStrategy implements MeritStrategy
{
    /**
     * @param  list<array<string, mixed>>  $inputs
     * @return list<array<string, mixed>>
     */
    public function rank(array $inputs): array
    {
        foreach ($inputs as $row) {
            if (array_key_exists('shortlisting_score', $row)) {
                throw new StatutoryViolation(
                    'UGC 2018 cl. 4.1 I Note: a shortlisting score must not enter a teaching merit list.'
                );
            }

            if (array_key_exists('written_test_score', $row)) {
                throw new StatutoryViolation(
                    'UGC 2018 cl. 5.3: a written-test score must not enter a teaching merit list.'
                );
            }
        }

        usort($inputs, static fn (array $a, array $b): int => ($b['interview_score'] ?? 0) <=> ($a['interview_score'] ?? 0));

        return $inputs;
    }
}
