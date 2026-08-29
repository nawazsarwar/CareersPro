<?php

declare(strict_types=1);

namespace App\Domain\Scrutiny;

use App\Enums\GateDecision;
use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * The scrutiny queue.
 *
 * The scope is applied FIRST and everything else narrows it: no filter, sort
 * or page can widen a queue past the officer's own faculty (M25-R05).
 *
 * Eager-loads are declared rather than discovered. The dossier row touches
 * several relations, and 100 rows times an unloaded relation is 100 queries on
 * a screen an officer refreshes all day.
 */
final class BuildQueue
{
    /**
     * @param  array<string, mixed>  $filters
     * @return Builder<Application>
     */
    public function for(User $user, array $filters = []): Builder
    {
        $query = Application::query()
            ->visibleTo($user)
            ->with([
                'user:id,name,email',
                'post:id,title,slug,ou_title_snapshot,ou_path_snapshot,post_type_id',
                'eligibilityDecisions',
            ])
            ->where('submitted', true);

        if (($filters['post_id'] ?? null) !== null) {
            $query->where('post_id', $filters['post_id']);
        }

        if (($filters['state'] ?? null) !== null) {
            $query->where('lifecycle_state', $filters['state']);
        }

        // Pending is its own filter, and it is not "rejected". The legacy UI
        // merged the two into one label on a legally consequential decision.
        if (($filters['scrutiny'] ?? null) === 'pending') {
            $query->whereHas('eligibilityDecisions', static fn (Builder $q) => $q
                ->where('gate', 'scrutiny')->whereNull('decision'));
        }

        if (($filters['scrutiny'] ?? null) === 'eligible') {
            $query->whereHas('eligibilityDecisions', static fn (Builder $q) => $q
                ->where('gate', 'scrutiny')->where('decision', GateDecision::Eligible->value));
        }

        if (($filters['scrutiny'] ?? null) === 'rejected') {
            $query->whereHas('eligibilityDecisions', static fn (Builder $q) => $q
                ->where('gate', 'scrutiny')->where('decision', GateDecision::Rejected->value));
        }

        // Payment is a precondition for scrutiny unless the application is
        // fee-exempt: examining an unpaid dossier wastes the officer's time
        // and the candidate's, since it cannot proceed either way.
        if (($filters['include_unpaid'] ?? false) !== true) {
            $query->where(static fn (Builder $q) => $q
                ->where('paid', true)
                ->orWhereDoesntHave('post.advertisement', static fn (Builder $ad) => $ad->where('default_fee', '>', 0)));
        }

        return $query->orderBy('application_no');
    }
}
