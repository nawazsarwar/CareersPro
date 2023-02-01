<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRefereeRequest;
use App\Http\Requests\UpdateRefereeRequest;
use App\Http\Resources\Admin\RefereeResource;
use App\Models\Referee;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RefereesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('referee_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RefereeResource(Referee::all());
    }

    public function store(StoreRefereeRequest $request)
    {
        $referee = Referee::create($request->all());

        return (new RefereeResource($referee))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Referee $referee)
    {
        abort_if(Gate::denies('referee_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RefereeResource($referee);
    }

    public function update(UpdateRefereeRequest $request, Referee $referee)
    {
        $referee->update($request->all());

        return (new RefereeResource($referee))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Referee $referee)
    {
        abort_if(Gate::denies('referee_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $referee->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
