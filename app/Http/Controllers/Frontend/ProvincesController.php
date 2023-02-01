<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProvinceRequest;
use App\Http\Requests\StoreProvinceRequest;
use App\Http\Requests\UpdateProvinceRequest;
use App\Models\Province;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProvincesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('province_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $provinces = Province::all();

        return view('frontend.provinces.index', compact('provinces'));
    }

    public function create()
    {
        abort_if(Gate::denies('province_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.provinces.create');
    }

    public function store(StoreProvinceRequest $request)
    {
        $province = Province::create($request->all());

        return redirect()->route('frontend.provinces.index');
    }

    public function edit(Province $province)
    {
        abort_if(Gate::denies('province_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.provinces.edit', compact('province'));
    }

    public function update(UpdateProvinceRequest $request, Province $province)
    {
        $province->update($request->all());

        return redirect()->route('frontend.provinces.index');
    }

    public function show(Province $province)
    {
        abort_if(Gate::denies('province_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.provinces.show', compact('province'));
    }

    public function destroy(Province $province)
    {
        abort_if(Gate::denies('province_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $province->delete();

        return back();
    }

    public function massDestroy(MassDestroyProvinceRequest $request)
    {
        Province::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
