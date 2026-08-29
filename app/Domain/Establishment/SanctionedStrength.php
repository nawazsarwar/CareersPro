<?php

declare(strict_types=1);

namespace App\Domain\Establishment;

use App\Models\Designation;
use App\Models\OrganisationalUnit;
use Illuminate\Support\Facades\DB;

final class SanctionedStrength
{
    public function for(OrganisationalUnit $unit, Designation $designation): int
    {
        return (int) DB::table('organisational_unit_designation')
            ->where('organisational_unit_id', $unit->getKey())
            ->where('designation_id', $designation->getKey())
            ->value('sanctioned_count');
    }

    public function filled(OrganisationalUnit $unit, Designation $designation): int
    {
        return (int) DB::table('organisational_unit_designation')
            ->where('organisational_unit_id', $unit->getKey())
            ->where('designation_id', $designation->getKey())
            ->value('filled_count');
    }
}
