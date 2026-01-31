<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDisabilityTypeRequest;
use App\Http\Requests\StoreDisabilityTypeRequest;
use App\Http\Requests\UpdateDisabilityTypeRequest;
use App\Models\DisabilityType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisabilityTypesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('disability_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $disabilityTypes = DisabilityType::all();

        return view('frontend.disabilityTypes.index', compact('disabilityTypes'));
    }

    public function create()
    {
        abort_if(Gate::denies('disability_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.disabilityTypes.create');
    }

    public function store(StoreDisabilityTypeRequest $request)
    {
        $disabilityType = DisabilityType::create($request->all());

        return redirect()->route('frontend.disability-types.index');
    }

    public function edit(DisabilityType $disabilityType)
    {
        abort_if(Gate::denies('disability_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.disabilityTypes.edit', compact('disabilityType'));
    }

    public function update(UpdateDisabilityTypeRequest $request, DisabilityType $disabilityType)
    {
        $disabilityType->update($request->all());

        return redirect()->route('frontend.disability-types.index');
    }

    public function show(DisabilityType $disabilityType)
    {
        abort_if(Gate::denies('disability_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.disabilityTypes.show', compact('disabilityType'));
    }

    public function destroy(DisabilityType $disabilityType)
    {
        abort_if(Gate::denies('disability_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $disabilityType->delete();

        return back();
    }

    public function massDestroy(MassDestroyDisabilityTypeRequest $request)
    {
        $disabilityTypes = DisabilityType::find(request('ids'));

        foreach ($disabilityTypes as $disabilityType) {
            $disabilityType->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
