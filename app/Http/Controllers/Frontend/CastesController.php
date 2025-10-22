<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCasteRequest;
use App\Http\Requests\StoreCasteRequest;
use App\Http\Requests\UpdateCasteRequest;
use App\Models\Caste;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CastesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('caste_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $castes = Caste::all();

        return view('frontend.castes.index', compact('castes'));
    }

    public function create()
    {
        abort_if(Gate::denies('caste_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.castes.create');
    }

    public function store(StoreCasteRequest $request)
    {
        $caste = Caste::create($request->all());

        return redirect()->route('frontend.castes.index');
    }

    public function edit(Caste $caste)
    {
        abort_if(Gate::denies('caste_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.castes.edit', compact('caste'));
    }

    public function update(UpdateCasteRequest $request, Caste $caste)
    {
        $caste->update($request->all());

        return redirect()->route('frontend.castes.index');
    }

    public function show(Caste $caste)
    {
        abort_if(Gate::denies('caste_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.castes.show', compact('caste'));
    }

    public function destroy(Caste $caste)
    {
        abort_if(Gate::denies('caste_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $caste->delete();

        return back();
    }

    public function massDestroy(MassDestroyCasteRequest $request)
    {
        $castes = Caste::find(request('ids'));

        foreach ($castes as $caste) {
            $caste->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
