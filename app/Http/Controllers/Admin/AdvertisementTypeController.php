<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAdvertisementTypeRequest;
use App\Http\Requests\StoreAdvertisementTypeRequest;
use App\Http\Requests\UpdateAdvertisementTypeRequest;
use App\Models\AdvertisementType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdvertisementTypeController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('advertisement_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisementTypes = AdvertisementType::all();

        return view('admin.advertisementTypes.index', compact('advertisementTypes'));
    }

    public function create()
    {
        abort_if(Gate::denies('advertisement_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.advertisementTypes.create');
    }

    public function store(StoreAdvertisementTypeRequest $request)
    {
        $advertisementType = AdvertisementType::create($request->all());

        return redirect()->route('admin.advertisement-types.index');
    }

    public function edit(AdvertisementType $advertisementType)
    {
        abort_if(Gate::denies('advertisement_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.advertisementTypes.edit', compact('advertisementType'));
    }

    public function update(UpdateAdvertisementTypeRequest $request, AdvertisementType $advertisementType)
    {
        $advertisementType->update($request->all());

        return redirect()->route('admin.advertisement-types.index');
    }

    public function show(AdvertisementType $advertisementType)
    {
        abort_if(Gate::denies('advertisement_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.advertisementTypes.show', compact('advertisementType'));
    }

    public function destroy(AdvertisementType $advertisementType)
    {
        abort_if(Gate::denies('advertisement_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisementType->delete();

        return back();
    }

    public function massDestroy(MassDestroyAdvertisementTypeRequest $request)
    {
        AdvertisementType::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
