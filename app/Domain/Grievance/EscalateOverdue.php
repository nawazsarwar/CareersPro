<?php

declare(strict_types=1);

namespace App\Domain\Grievance;

use App\Models\Grievance;
use Carbon\CarbonImmutable;

/**
 * An overdue grievance escalates rather than ageing quietly.
 *
 * Marking it is what makes the SLA real: an unescalated overdue item is
 * indistinguishable from one nobody has looked at, and both look like an empty
 * queue on a dashboard.
 */
final class EscalateOverdue
{
    public function handle(): int
    {
        return Grievance::query()
            ->whereNull('resolved_at')
            ->whereNull('escalated_at')
            ->whereNotNull('due_at')
            ->where('due_at', '<', CarbonImmutable::now())
            ->update([
                'escalated_at' => CarbonImmutable::now(),
                'status' => 'escalated',
            ]);
    }
}
