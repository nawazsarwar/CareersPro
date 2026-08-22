<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdvertisementTypeRequest;
use App\Http\Requests\UpdateAdvertisementTypeRequest;
use App\Http\Resources\Admin\AdvertisementTypeResource;
use App\Models\AdvertisementType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdvertisementTypesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('advertisement_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AdvertisementTypeResource(AdvertisementType::all());
    }

    public function store(StoreAdvertisementTypeRequest $request)
    {
        $advertisementType = AdvertisementType::create($request->all());

        return (new AdvertisementTypeResource($advertisementType))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AdvertisementType $advertisementType)
    {
        abort_if(Gate::denies('advertisement_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AdvertisementTypeResource($advertisementType);
    }

    public function update(UpdateAdvertisementTypeRequest $request, AdvertisementType $advertisementType)
    {
        $advertisementType->update($request->all());

        return (new AdvertisementTypeResource($advertisementType))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(AdvertisementType $advertisementType)
    {
        abort_if(Gate::denies('advertisement_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisementType->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
