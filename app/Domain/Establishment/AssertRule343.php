<?php

declare(strict_types=1);

namespace App\Domain\Establishment;

use App\Models\Designation;
use App\Models\OrganisationalUnit;
use RuntimeException;

/**
 * CRR Rule 34.3.
 *
 * *"Wherever there is only one sanctioned post in any cadre, the post shall be
 * filled through direct recruitment only."*
 *
 * A single-post cadre cannot have a promotion ladder, so filling it by
 * promotion would promote somebody into the only post there is. The rule is
 * enforced rather than documented because the alternative is an appointment a
 * disappointed candidate can have set aside.
 */
final class AssertRule343
{
    public const DIRECT_RECRUITMENT = 'direct_recruitment';

    public function __construct(private readonly SanctionedStrength $strength) {}

    public function check(OrganisationalUnit $unit, Designation $designation, string $method): void
    {
        if ($this->strength->for($unit, $designation) !== 1) {
            return;
        }

        if ($method === self::DIRECT_RECRUITMENT) {
            return;
        }

        throw new RuntimeException(sprintf(
            'CRR Rule 34.3: %s at %s has one sanctioned post, so it may be filled by direct recruitment only, not by %s.',
            $designation->code,
            $unit->code,
            $method,
        ));
    }
}
