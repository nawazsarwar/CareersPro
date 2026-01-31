<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInstitutionsAttendedRequest;
use App\Http\Requests\UpdateInstitutionsAttendedRequest;
use App\Http\Resources\Admin\InstitutionsAttendedResource;
use App\Models\InstitutionsAttended;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InstitutionsAttendedApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('institutions_attended_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InstitutionsAttendedResource(InstitutionsAttended::with(['user', 'university_board'])->get());
    }

    public function store(StoreInstitutionsAttendedRequest $request)
    {
        $institutionsAttended = InstitutionsAttended::create($request->all());

        return (new InstitutionsAttendedResource($institutionsAttended))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(InstitutionsAttended $institutionsAttended)
    {
        abort_if(Gate::denies('institutions_attended_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InstitutionsAttendedResource($institutionsAttended->load(['user', 'university_board']));
    }

    public function update(UpdateInstitutionsAttendedRequest $request, InstitutionsAttended $institutionsAttended)
    {
        $institutionsAttended->update($request->all());

        return (new InstitutionsAttendedResource($institutionsAttended))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(InstitutionsAttended $institutionsAttended)
    {
        abort_if(Gate::denies('institutions_attended_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $institutionsAttended->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
