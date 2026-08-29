<?php

declare(strict_types=1);

namespace App\Domain\Recruitment;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

/**
 * A closing date falling on a declared holiday moves to the next working day.
 *
 * A candidate who cannot reach a bank or a post office on the closing date has
 * been given a shorter window than the advertisement states, and the move is
 * recorded so the extension is visible rather than silent.
 */
final class NextWorkingDay
{
    /**
     * @param  list<string>  $holidays  Y-m-d dates
     */
    public function from(CarbonInterface $date, array $holidays = []): CarbonImmutable
    {
        $candidate = CarbonImmutable::parse($date->toDateString());

        // Guarded rather than while(true): a holiday list that accidentally
        // covered every day would otherwise hang the request.
        for ($i = 0; $i < 30; $i++) {
            if (! $this->isClosed($candidate, $holidays)) {
                return $candidate;
            }

            $candidate = $candidate->addDay();
        }

        return $candidate;
    }

    /**
     * @param  list<string>  $holidays
     */
    public function isClosed(CarbonImmutable $date, array $holidays = []): bool
    {
        return $date->isSunday() || in_array($date->toDateString(), $holidays, true);
    }
}
