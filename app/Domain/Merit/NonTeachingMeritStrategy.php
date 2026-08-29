<?php

declare(strict_types=1);

namespace App\Domain\Merit;

/**
 * Non-teaching merit is additive.
 *
 * CRR Rule 11 III(f)–(g): Paper I and Paper II count towards the total and the
 * interview carries a 20 per cent weighting. This is the exact opposite of the
 * teaching regime, which is why the two are separate classes rather than one
 * with a flag -- a flag is something a caller can get wrong, and getting this
 * one wrong is a statutory violation in either direction.
 *
 * Unresolved and recorded rather than assumed: Rule 11 III(g) builds an
 * interview into the Group B and C merit list, while Rule 22.8 forbids any
 * interview for those groups, citing MHRD letter 19-50/2015-Desk-U. The source
 * does not reconcile them and OQ-008 is with the legal cell. Until it returns,
 * an interview score for a Group B or C post is refused rather than weighted.
 */
final class NonTeachingMeritStrategy implements MeritStrategy
{
    /**
     * @param  list<array<string, mixed>>  $inputs
     * @return list<array<string, mixed>>
     */
    public function rank(array $inputs): array
    {
        foreach ($inputs as $index => $row) {
            $group = $row['group'] ?? null;

            if (in_array($group, ['B', 'C'], true) && array_key_exists('interview_score', $row)) {
                throw new StatutoryViolation(
                    'CRR Rule 22.8 forbids an interview for Group B and C posts, while Rule 11 III(g) weights one. '
                    .'The conflict is unresolved (OQ-008), so an interview score for this group is refused rather than guessed at.'
                );
            }

            $inputs[$index]['total'] = ($row['paper_one_score'] ?? 0)
                + ($row['paper_two_score'] ?? 0)
                + (($row['interview_score'] ?? 0) * ($row['interview_weight'] ?? 0.2));
        }

        usort($inputs, static fn (array $a, array $b): int => $b['total'] <=> $a['total']);

        return $inputs;
    }
}
