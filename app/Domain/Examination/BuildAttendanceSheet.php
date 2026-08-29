<?php

declare(strict_types=1);

namespace App\Domain\Examination;

use App\Enums\EligibilityGate;
use App\Enums\GateDecision;
use App\Models\Application;
use App\Models\ExamCentre;
use App\Models\Post;
use Illuminate\Support\Collection;

/**
 * The attendance sheet an invigilator actually carries.
 *
 * Ordered by roll number within a room, because that is the order the room is
 * laid out in and an invigilator working down a differently-sorted list will
 * mark the wrong person present.
 *
 * The cohort filters mirror the legacy Bulk Document screen -- "eligible only"
 * and "interview eligible only" -- which the previous redesign could not
 * compute at all, having collapsed the three gates into four generic columns.
 */
final class BuildAttendanceSheet
{
    public const ALL = 'all';

    public const SCRUTINY_ELIGIBLE = 'scrutiny_eligible';

    public const INTERVIEW_ELIGIBLE = 'interview_eligible';

    /**
     * @return Collection<int, Application>
     */
    public function for(Post $post, ExamCentre $centre, string $cohort = self::SCRUTINY_ELIGIBLE): Collection
    {
        $query = Application::query()
            ->where('post_id', $post->getKey())
            ->where('centre_id', $centre->getKey())
            ->with(['user:id,name', 'seatAllocation']);

        $gate = match ($cohort) {
            self::SCRUTINY_ELIGIBLE => EligibilityGate::Scrutiny,
            self::INTERVIEW_ELIGIBLE => EligibilityGate::Interview,
            default => null,
        };

        if ($gate !== null) {
            $query->whereHas('eligibilityDecisions', static fn ($inner) => $inner
                ->where('gate', $gate->value)
                ->where('decision', GateDecision::Eligible->value));
        }

        return $query
            ->orderBy('room_no')
            ->orderByRaw('CAST(seat_no AS UNSIGNED)')
            ->get();
    }
}
