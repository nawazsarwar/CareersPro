<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmploymentHistoryRequest;
use App\Http\Requests\UpdateEmploymentHistoryRequest;
use App\Http\Resources\Admin\EmploymentHistoryResource;
use App\Models\EmploymentHistory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmploymentHistoryApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('employment_history_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EmploymentHistoryResource(EmploymentHistory::with(['user'])->get());
    }

    public function store(StoreEmploymentHistoryRequest $request)
    {
        $employmentHistory = EmploymentHistory::create($request->all());

        return (new EmploymentHistoryResource($employmentHistory))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(EmploymentHistory $employmentHistory)
    {
        abort_if(Gate::denies('employment_history_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EmploymentHistoryResource($employmentHistory->load(['user']));
    }

    public function update(UpdateEmploymentHistoryRequest $request, EmploymentHistory $employmentHistory)
    {
        $employmentHistory->update($request->all());

        return (new EmploymentHistoryResource($employmentHistory))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(EmploymentHistory $employmentHistory)
    {
        abort_if(Gate::denies('employment_history_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employmentHistory->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
