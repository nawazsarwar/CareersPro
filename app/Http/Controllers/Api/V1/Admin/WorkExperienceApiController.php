<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkExperienceRequest;
use App\Http\Requests\UpdateWorkExperienceRequest;
use App\Http\Resources\Admin\WorkExperienceResource;
use App\Models\WorkExperience;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkExperienceApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('work_experience_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WorkExperienceResource(WorkExperience::with(['user'])->get());
    }

    public function store(StoreWorkExperienceRequest $request)
    {
        $workExperience = WorkExperience::create($request->all());

        return (new WorkExperienceResource($workExperience))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(WorkExperience $workExperience)
    {
        abort_if(Gate::denies('work_experience_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WorkExperienceResource($workExperience->load(['user']));
    }

    public function update(UpdateWorkExperienceRequest $request, WorkExperience $workExperience)
    {
        $workExperience->update($request->all());

        return (new WorkExperienceResource($workExperience))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(WorkExperience $workExperience)
    {
        abort_if(Gate::denies('work_experience_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workExperience->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
