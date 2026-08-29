<?php

declare(strict_types=1);

namespace App\Domain\Examination;

use App\Enums\EligibilityGate;
use App\Enums\GateDecision;
use App\Models\Application;
use App\Models\ExamCentre;
use App\Models\Post;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\QueryException;

/**
 * Seats candidates, honouring preference where capacity allows.
 *
 * Clash-free by construction rather than by care: the unique index on
 * (centre, room, seat) makes a double allocation impossible. Where two
 * concurrent runs race for the same seat the database refuses one of them and
 * the loser takes the next seat, which is the behaviour you want -- rather
 * than both succeeding and two people arriving at one desk.
 *
 * Only candidates whose relevant gate reads `eligible` are seated. Seating
 * somebody who has not cleared scrutiny tells them they are in the
 * examination.
 */
final class AllocateSeats
{
    private const SEATS_PER_ROOM = 30;

    public function __construct(
        private readonly ConnectionInterface $connection,
        private readonly AllocateRollNumber $rollNumbers,
    ) {}

    public function handle(Post $post): AllocationReport
    {
        $placed = [];
        $unplaced = [];

        $eligible = Application::query()
            ->where('post_id', $post->getKey())
            ->whereHas('eligibilityDecisions', static fn ($query) => $query
                ->where('gate', EligibilityGate::Scrutiny->value)
                ->where('decision', GateDecision::Eligible->value))
            ->with('centrePreferences')
            ->orderBy('application_no')
            ->get();

        $centres = ExamCentre::query()->where('is_active', true)->orderBy('id')->get();

        if ($centres->isEmpty()) {
            return new AllocationReport([], $eligible->map(static fn (Application $a): array => [
                'application_id' => (int) $a->getKey(),
                'reason' => 'no_active_centre',
            ])->all());
        }

        foreach ($eligible as $application) {
            $this->connection->transaction(function () use ($application, $post, $centres, &$placed, &$unplaced): void {
                $this->rollNumbers->handle($application);

                // Preference first, then anything with room. "Proximity" needs
                // a distance the master does not yet carry, so the fallback is
                // declared as `fallback_any` rather than pretending to be
                // something it is not.
                $preferred = $application->centrePreferences
                    ->sortBy('preference_order')
                    ->pluck('exam_centre_id')
                    ->all();

                $ordered = $centres->sortBy(
                    static fn (ExamCentre $centre): int => ($index = array_search($centre->getKey(), $preferred, true)) === false
                        ? PHP_INT_MAX
                        : $index,
                );

                foreach ($ordered as $centre) {
                    $rule = in_array($centre->getKey(), $preferred, true) ? 'preference' : 'fallback_any';
                    $seat = $this->nextSeat($centre);

                    if ($seat === null) {
                        continue;
                    }

                    try {
                        $application->seatAllocation()->create([
                            'post_id' => $post->getKey(),
                            'exam_centre_id' => $centre->getKey(),
                            'room_no' => $seat['room'],
                            'seat_no' => $seat['seat'],
                            'allocation_rule' => $rule,
                        ]);
                    } catch (QueryException) {
                        // Another run took this seat between our read and our
                        // write. The index did its job; try the next centre.
                        continue;
                    }

                    $application->forceFill([
                        'centre_id' => $centre->getKey(),
                        'room_no' => $seat['room'],
                        'seat_no' => (string) $seat['seat'],
                    ])->save();

                    $placed[] = [
                        'application_id' => (int) $application->getKey(),
                        'centre_id' => (int) $centre->getKey(),
                        'rule' => $rule,
                    ];

                    return;
                }

                $unplaced[] = [
                    'application_id' => (int) $application->getKey(),
                    'reason' => 'no_capacity',
                ];
            });
        }

        return new AllocationReport($placed, $unplaced);
    }

    /**
     * @return array{room: string, seat: int}|null
     */
    private function nextSeat(ExamCentre $centre): ?array
    {
        $taken = (int) $this->connection->table('seat_allocations')
            ->where('exam_centre_id', $centre->getKey())
            ->count();

        // Capacity is a hard limit, always. Treating 0 as "unlimited" would
        // mean a centre nobody had configured silently seated everybody --
        // and the failure would surface as candidates arriving at a room with
        // no desks.
        if ($taken >= $centre->capacity) {
            return null;
        }

        return [
            'room' => 'R'.str_pad((string) (intdiv($taken, self::SEATS_PER_ROOM) + 1), 2, '0', STR_PAD_LEFT),
            'seat' => ($taken % self::SEATS_PER_ROOM) + 1,
        ];
    }
}
