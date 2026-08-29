<?php

declare(strict_types=1);

namespace App\Domain\Communication;

use App\Enums\EligibilityGate;
use App\Enums\GateDecision;
use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * Who a campaign is for.
 *
 * The segment is stored on the campaign as the filter, not as a list of
 * addresses. Storing the filter means the audit answers "who did you decide to
 * write to and why", which is the question asked afterwards -- and it stops a
 * campaign silently going to a cohort that has changed since it was drafted.
 *
 * The scope is applied first, as everywhere else: an officer cannot mail
 * another faculty's candidates by widening a filter.
 */
final class ResolveSegment
{
    /**
     * @param  array<string, mixed>  $segment
     * @return Builder<Application>
     */
    public function for(User $actor, array $segment): Builder
    {
        $query = Application::query()
            ->visibleTo($actor)
            ->where('submitted', true)
            ->with('user:id,name,email');

        if (($segment['post_id'] ?? null) !== null) {
            $query->where('post_id', $segment['post_id']);
        }

        if (($segment['lifecycle_state'] ?? null) !== null) {
            $query->where('lifecycle_state', $segment['lifecycle_state']);
        }

        if (($segment['paid'] ?? null) !== null) {
            $query->where('paid', (bool) $segment['paid']);
        }

        $gate = $segment['gate'] ?? null;
        $decision = $segment['gate_decision'] ?? null;

        if ($gate !== null) {
            $query->whereHas('eligibilityDecisions', function (Builder $inner) use ($gate, $decision): void {
                $inner->where('gate', EligibilityGate::from((string) $gate)->value);

                // Explicitly distinguishing pending from rejected, here as
                // everywhere: a mailing to "not eligible" that swept up
                // undecided candidates would tell them they had been refused.
                if ($decision === 'pending') {
                    $inner->whereNull('decision');

                    return;
                }

                if ($decision !== null) {
                    $inner->where('decision', GateDecision::from((string) $decision)->value);
                }
            });
        }

        return $query;
    }
}
