<?php

declare(strict_types=1);

namespace App\Domain\Eligibility;

use App\Enums\EligibilityGate;
use App\Enums\GateDecision;
use App\Models\Application;
use RuntimeException;

/**
 * Gates are ordered but independent (M34 §3).
 *
 * Ordered: a written test cannot be decided before scrutiny has cleared,
 * because a candidate who failed scrutiny should never have sat one.
 *
 * Independent: a written-test rejection does not alter the scrutiny decision.
 * Collapsing them into one status is what left the legacy column unable to say
 * "cleared scrutiny, failed the test", which is a different thing from
 * "rejected at scrutiny" and carries different rights.
 */
final class GateOrder
{
    /**
     * The order in which gates become decidable.
     *
     * @var list<value-of<EligibilityGate>>
     */
    private const SEQUENCE = ['scrutiny', 'written_test', 'interview'];

    public function assertDecidable(Application $application, EligibilityGate $gate): void
    {
        $position = array_search($gate->value, self::SEQUENCE, true);

        if ($position === false || $position === 0) {
            return;
        }

        $decisions = $application->eligibilityDecisions->keyBy(
            static fn ($decision): string => $decision->gate->value,
        );

        // Every earlier gate this post actually has must have cleared first.
        foreach (array_slice(self::SEQUENCE, 0, $position) as $earlier) {
            $decision = $decisions->get($earlier);

            if ($decision === null) {
                continue;      // not an active gate for this post
            }

            if ($decision->decision !== GateDecision::Eligible) {
                throw new RuntimeException(sprintf(
                    'The %s gate cannot be decided until %s has cleared.',
                    $gate->value,
                    $earlier,
                ));
            }
        }
    }
}
