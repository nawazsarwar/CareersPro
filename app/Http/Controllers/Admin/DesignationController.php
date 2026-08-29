<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDesignationRequest;
use App\Models\Designation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignationController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Designation::class);

        return view('admin.designations.index', [
            'designations' => Designation::query()
                ->visibleTo($request->user())
                ->when($request->filled('cadre'), fn ($q) => $q->where('cadre', $request->string('cadre')))
                ->orderBy('cadre')
                ->orderBy('name')
                ->paginate(50)
                ->withQueryString(),
        ]);
    }

    public function show(Designation $designation): View
    {
        $this->authorize('view', $designation);

        return view('admin.designations.show', [
            'designation' => $designation->load('organisationalUnits'),
        ]);
    }

    public function store(StoreDesignationRequest $request): RedirectResponse
    {
        $this->authorize('create', Designation::class);

        $designation = Designation::query()->create($request->validated());

        return redirect()
            ->route('admin.designations.show', $designation)
            ->with('status', __('establishment.designation_created'));
    }

    public function update(StoreDesignationRequest $request, Designation $designation): RedirectResponse
    {
        $this->authorize('update', $designation);

        $designation->update($request->validated());

        return back()->with('status', __('establishment.designation_updated'));
    }
}
