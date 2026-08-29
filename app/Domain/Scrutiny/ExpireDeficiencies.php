<?php

declare(strict_types=1);

namespace App\Domain\Scrutiny;

use App\Domain\Eligibility\DecideGate;
use App\Enums\EligibilityGate;
use App\Enums\GateDecision;
use App\Models\Deficiency;
use App\Models\User;
use Carbon\CarbonImmutable;

/**
 * An unrectified deficiency becomes a decision, not a silence.
 *
 * A window that simply lapses leaves the candidate in limbo and leaves the
 * University with an application nobody ever decided. The scrutiny gate is set
 * to rejected with a stated reason, which is appealable -- an outcome the
 * candidate can see and challenge, rather than an absence they cannot.
 */
final class ExpireDeficiencies
{
    public function __construct(private readonly DecideGate $decideGate) {}

    public function handle(?User $systemActor = null): int
    {
        $expired = 0;

        $overdue = Deficiency::query()
            ->whereNull('rectified_at')
            ->whereNotNull('rectification_window_closes_at')
            ->where('rectification_window_closes_at', '<', CarbonImmutable::now())
            ->with('application.eligibilityDecisions', 'application.post')
            ->get();

        foreach ($overdue as $deficiency) {
            $deficiency->forceFill([
                'resolution' => __('scrutiny.window_expired'),
            ])->save();

            $actor = $systemActor ?? User::query()->find($deficiency->raised_by_id);

            if ($actor === null) {
                continue;
            }

            $this->decideGate->handle(
                $deficiency->application,
                EligibilityGate::Scrutiny,
                GateDecision::Rejected,
                __('scrutiny.deficiency_not_rectified'),
                $actor,
            );

            $expired++;
        }

        return $expired;
    }
}
