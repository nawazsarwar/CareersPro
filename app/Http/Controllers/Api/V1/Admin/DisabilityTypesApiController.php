<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDisabilityTypeRequest;
use App\Http\Requests\UpdateDisabilityTypeRequest;
use App\Http\Resources\Admin\DisabilityTypeResource;
use App\Models\DisabilityType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisabilityTypesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('disability_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DisabilityTypeResource(DisabilityType::all());
    }

    public function store(StoreDisabilityTypeRequest $request)
    {
        $disabilityType = DisabilityType::create($request->all());

        return (new DisabilityTypeResource($disabilityType))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DisabilityType $disabilityType)
    {
        abort_if(Gate::denies('disability_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DisabilityTypeResource($disabilityType);
    }

    public function update(UpdateDisabilityTypeRequest $request, DisabilityType $disabilityType)
    {
        $disabilityType->update($request->all());

        return (new DisabilityTypeResource($disabilityType))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DisabilityType $disabilityType)
    {
        abort_if(Gate::denies('disability_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $disabilityType->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
