<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMaritalStatusRequest;
use App\Http\Requests\UpdateMaritalStatusRequest;
use App\Http\Resources\Admin\MaritalStatusResource;
use App\Models\MaritalStatus;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaritalStatusesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('marital_status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MaritalStatusResource(MaritalStatus::all());
    }

    public function store(StoreMaritalStatusRequest $request)
    {
        $maritalStatus = MaritalStatus::create($request->all());

        return (new MaritalStatusResource($maritalStatus))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(MaritalStatus $maritalStatus)
    {
        abort_if(Gate::denies('marital_status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MaritalStatusResource($maritalStatus);
    }

    public function update(UpdateMaritalStatusRequest $request, MaritalStatus $maritalStatus)
    {
        $maritalStatus->update($request->all());

        return (new MaritalStatusResource($maritalStatus))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(MaritalStatus $maritalStatus)
    {
        abort_if(Gate::denies('marital_status_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $maritalStatus->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
