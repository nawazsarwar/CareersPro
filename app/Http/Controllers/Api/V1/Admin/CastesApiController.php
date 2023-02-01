<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCasteRequest;
use App\Http\Requests\UpdateCasteRequest;
use App\Http\Resources\Admin\CasteResource;
use App\Models\Caste;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CastesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('caste_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CasteResource(Caste::all());
    }

    public function store(StoreCasteRequest $request)
    {
        $caste = Caste::create($request->all());

        return (new CasteResource($caste))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Caste $caste)
    {
        abort_if(Gate::denies('caste_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CasteResource($caste);
    }

    public function update(UpdateCasteRequest $request, Caste $caste)
    {
        $caste->update($request->all());

        return (new CasteResource($caste))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Caste $caste)
    {
        abort_if(Gate::denies('caste_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $caste->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
