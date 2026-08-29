<?php

declare(strict_types=1);

namespace App\Domain\Dossier;

use Carbon\CarbonImmutable;

/**
 * Age on the crucial date, which is the closing date of the application and
 * never today (CRR Rule 14).
 *
 * The distinction is not academic. A candidate who is inside the age limit on
 * the closing date and outside it by the time scrutiny happens is eligible, and
 * computing against `now()` would reject them for the University's own delay.
 */
final class ComputeAge
{
    public function on(CarbonImmutable $dateOfBirth, CarbonImmutable $crucialDate): int
    {
        return (int) $dateOfBirth->diffInYears($crucialDate);
    }

    public function exceedsLimit(CarbonImmutable $dateOfBirth, CarbonImmutable $crucialDate, ?int $limit): bool
    {
        if ($limit === null) {
            return false;
        }

        return $this->on($dateOfBirth, $crucialDate) > $limit;
    }
}
