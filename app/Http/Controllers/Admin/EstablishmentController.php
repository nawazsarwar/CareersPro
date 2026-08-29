<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateEstablishmentRequest;
use App\Models\Designation;
use App\Models\OrganisationalUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * The sanctioned-strength register (CRR Rules 8 and 9.1).
 *
 * It exists in no system today: MODULES.md #16 promised "post creation linked
 * to sanctioned strength" with no backing data in either database.
 */
class EstablishmentController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', OrganisationalUnit::class);

        $rows = DB::table('organisational_unit_designation as od')
            ->join('organisational_units as ou', 'ou.id', '=', 'od.organisational_unit_id')
            ->join('designations as d', 'd.id', '=', 'od.designation_id')
            ->select([
                'ou.title as unit', 'ou.code as unit_code',
                'd.name as designation', 'd.code as designation_code',
                'od.sanctioned_count', 'od.filled_count', 'od.sanction_order_ref',
            ])
            ->orderBy('ou.path')
            ->orderBy('d.name')
            ->paginate(100);

        return view('admin.establishment.index', ['rows' => $rows]);
    }

    public function update(
        UpdateEstablishmentRequest $request,
        OrganisationalUnit $organisationalUnit,
        Designation $designation,
    ): RedirectResponse {
        $this->authorize('update', OrganisationalUnit::class);

        // updateOrInsert rather than a model: the register is a pivot, and
        // giving it a model would invite somebody to attach behaviour to what
        // is a ledger row.
        DB::table('organisational_unit_designation')->updateOrInsert(
            [
                'organisational_unit_id' => $organisationalUnit->getKey(),
                'designation_id' => $designation->getKey(),
            ],
            [
                'sanctioned_count' => $request->integer('sanctioned_count'),
                'sanction_order_ref' => $request->string('sanction_order_ref'),
                'sanctioned_on' => $request->date('sanctioned_on'),
                'updated_at' => now(),
                'created_at' => now(),
            ],
        );

        return back()->with('status', __('establishment.updated'));
    }
}
