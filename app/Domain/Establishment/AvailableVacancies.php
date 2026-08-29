<?php

declare(strict_types=1);

namespace App\Domain\Establishment;

use App\Models\Designation;
use App\Models\OrganisationalUnit;
use RuntimeException;

/**
 * Sanctioned minus filled minus already advertised and open.
 *
 * It never returns a negative. An over-advertisement is a hard error rather
 * than a warning, because advertising a post the University has not sanctioned
 * is a commitment it cannot lawfully honour, and the candidates who apply to
 * it have a claim.
 */
final class AvailableVacancies
{
    public function __construct(private readonly SanctionedStrength $strength) {}

    public function for(OrganisationalUnit $unit, Designation $designation, int $advertisedAndOpen = 0): int
    {
        $sanctioned = $this->strength->for($unit, $designation);
        $filled = $this->strength->filled($unit, $designation);

        $available = $sanctioned - $filled - $advertisedAndOpen;

        if ($available < 0) {
            throw new RuntimeException(sprintf(
                'Establishment overcommitted: %s at %s has %d sanctioned, %d filled and %d advertised.',
                $designation->code,
                $unit->code,
                $sanctioned,
                $filled,
                $advertisedAndOpen,
            ));
        }

        return $available;
    }
}
