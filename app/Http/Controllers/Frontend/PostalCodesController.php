<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPostalCodeRequest;
use App\Http\Requests\StorePostalCodeRequest;
use App\Http\Requests\UpdatePostalCodeRequest;
use App\Models\PostalCode;
use App\Models\Province;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PostalCodesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('postal_code_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $postalCodes = PostalCode::with(['province'])->get();

        return view('frontend.postalCodes.index', compact('postalCodes'));
    }

    public function create()
    {
        abort_if(Gate::denies('postal_code_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $provinces = Province::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.postalCodes.create', compact('provinces'));
    }

    public function store(StorePostalCodeRequest $request)
    {
        $postalCode = PostalCode::create($request->all());

        return redirect()->route('frontend.postal-codes.index');
    }

    public function edit(PostalCode $postalCode)
    {
        abort_if(Gate::denies('postal_code_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $provinces = Province::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $postalCode->load('province');

        return view('frontend.postalCodes.edit', compact('postalCode', 'provinces'));
    }

    public function update(UpdatePostalCodeRequest $request, PostalCode $postalCode)
    {
        $postalCode->update($request->all());

        return redirect()->route('frontend.postal-codes.index');
    }

    public function show(PostalCode $postalCode)
    {
        abort_if(Gate::denies('postal_code_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $postalCode->load('province');

        return view('frontend.postalCodes.show', compact('postalCode'));
    }

    public function destroy(PostalCode $postalCode)
    {
        abort_if(Gate::denies('postal_code_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $postalCode->delete();

        return back();
    }

    public function massDestroy(MassDestroyPostalCodeRequest $request)
    {
        PostalCode::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
