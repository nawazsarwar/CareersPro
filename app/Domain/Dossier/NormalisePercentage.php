<?php

declare(strict_types=1);

namespace App\Domain\Dossier;

use App\Models\AcademicQualification;

/**
 * CGPA to percentage, by the formula the candidate declares (DR-016).
 *
 * UGC 2018 cl. 3.6 defers to *the awarding university's* own formula, and there
 * are hundreds of them. There is therefore no single algorithm to implement,
 * and inventing one would silently change a candidate's marks. The candidate
 * declares the formula with documentary proof; until that proof is verified
 * the result is provisional and cannot clear a threshold on its own.
 */
final class NormalisePercentage
{
    public function from(AcademicQualification $qualification): ?float
    {
        // A stated percentage wins. It is what the certificate says.
        if ($qualification->percentage !== null) {
            return (float) $qualification->percentage;
        }

        $declaration = $qualification->conversion_declaration;

        if (! is_array($declaration) || $qualification->cgpa === null) {
            return null;
        }

        $multiplier = $declaration['multiplier'] ?? null;
        $offset = $declaration['offset'] ?? 0;

        if (! is_numeric($multiplier)) {
            return null;
        }

        // The AMU-declared shape: (CGPA − offset) × multiplier. A CGPA of 6.28
        // with offset 0.75 and multiplier 10 yields 55.3%.
        return round(((float) $qualification->cgpa - (float) $offset) * (float) $multiplier, 2);
    }

    /**
     * Whether the figure may be relied on to clear a statutory threshold.
     *
     * A declared conversion without verified proof may not: the difference
     * between 54.9% and 55% is the difference between eligible and not.
     */
    public function isProvisional(AcademicQualification $qualification): bool
    {
        if ($qualification->percentage !== null) {
            return false;
        }

        $declaration = $qualification->conversion_declaration;

        return ! is_array($declaration) || ($declaration['verified'] ?? false) !== true;
    }
}
