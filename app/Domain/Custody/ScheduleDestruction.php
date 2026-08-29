<?php

declare(strict_types=1);

namespace App\Domain\Custody;

use App\Enums\LifecycleState;
use App\Models\HardcopyReceipt;
use App\Models\Post;
use Carbon\CarbonImmutable;

/**
 * Five years after the process closes, for unsuccessful candidates only
 * (DR-011).
 *
 * The weeding is a physical-custody process, not a data-deletion one. Nothing
 * here touches an application: the electronic record is retained indefinitely
 * and the hash chain stays unbroken. What acquires a destruction date is a box
 * of paper.
 *
 * A candidate who was selected and joined never acquires one -- their dossier
 * is retained permanently in the central record section, and CRR Rule 22.4
 * permits verification at any point even after joining.
 */
final class ScheduleDestruction
{
    private const RETENTION_YEARS = 5;

    public function handle(Post $post, ?CarbonImmutable $processClosedAt = null): int
    {
        $closedAt = $processClosedAt ?? CarbonImmutable::now();
        $dueOn = $closedAt->addYears(self::RETENTION_YEARS)->toDateString();

        return HardcopyReceipt::query()
            ->whereNull('destruction_due_on')
            ->whereNull('destruction_batch_id')
            ->whereHas('application', static fn ($query) => $query
                ->where('post_id', $post->getKey())
                // Unsuccessful only. Selected candidates are kept for good.
                ->whereNotIn('lifecycle_state', [
                    LifecycleState::Selected->value,
                    LifecycleState::Waitlisted->value,
                ]))
            ->update(['destruction_due_on' => $dueOn]);
    }
}
