<?php

declare(strict_types=1);

namespace App\Domain\Recruitment;

use App\Models\Advertisement;
use Carbon\CarbonImmutable;
use RuntimeException;

/**
 * The thirty-day advertisement window.
 *
 * A shorter window is not a policy preference: it is the statutory minimum
 * notice a candidate is owed, and an appointment made on a shorter one is
 * challengeable. Enforced at publish, where the clock actually starts, rather
 * than at draft where the dates can still move.
 */
final class AssertWindow
{
    public const MINIMUM_DAYS = 30;

    public function check(Advertisement $advertisement, ?CarbonImmutable $publishAt = null): void
    {
        $closing = $advertisement->default_closing_date;

        if ($closing === null) {
            throw new RuntimeException('An advertisement cannot be published without a closing date.');
        }

        $from = $publishAt ?? CarbonImmutable::now();
        $days = $from->startOfDay()->diffInDays(CarbonImmutable::parse($closing->toDateString())->endOfDay());

        if ($days < self::MINIMUM_DAYS) {
            throw new RuntimeException(sprintf(
                'The advertisement window is %d days. The statutory minimum is %d.',
                (int) $days,
                self::MINIMUM_DAYS,
            ));
        }
    }
}
