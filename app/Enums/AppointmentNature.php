<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * DR-010. Not a naming quirk in the legacy data: two genuinely different
 * regimes.
 *
 * General is permanent, to superannuation, administered centrally by a General
 * Selection Committee. Local is temporary at six to twelve months, chaired by
 * the Dean and administered in the Dean's office. Eligibility, fee and the
 * thirty-day window are identical; the administration is not, which is why it
 * drives an authorisation scope rather than a label.
 */
enum AppointmentNature: string
{
    case General = 'general';
    case Local = 'local';

    public function requiresTenure(): bool
    {
        return $this === self::Local;
    }

    /**
     * Local recruitment is administered at faculty level, so a local
     * advertisement must name the unit that administers it.
     */
    public function requiresOrganisationalUnit(): bool
    {
        return $this === self::Local;
    }

    public function label(): string
    {
        return match ($this) {
            self::General => __('recruitment.nature_general'),
            self::Local => __('recruitment.nature_local'),
        };
    }
}
