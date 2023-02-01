<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWorkExperienceRequest;
use App\Http\Requests\StoreWorkExperienceRequest;
use App\Http\Requests\UpdateWorkExperienceRequest;
use App\Models\WorkExperience;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkExperiencesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('work_experience_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workExperiences = WorkExperience::all();

        return view('frontend.workExperiences.index', compact('workExperiences'));
    }

    public function create()
    {
        abort_if(Gate::denies('work_experience_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.workExperiences.create');
    }

    public function store(StoreWorkExperienceRequest $request)
    {
        $workExperience = WorkExperience::create($request->all());

        return redirect()->route('frontend.work-experiences.index');
    }

    public function edit(WorkExperience $workExperience)
    {
        abort_if(Gate::denies('work_experience_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.workExperiences.edit', compact('workExperience'));
    }

    public function update(UpdateWorkExperienceRequest $request, WorkExperience $workExperience)
    {
        $workExperience->update($request->all());

        return redirect()->route('frontend.work-experiences.index');
    }

    public function show(WorkExperience $workExperience)
    {
        abort_if(Gate::denies('work_experience_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.workExperiences.show', compact('workExperience'));
    }

    public function destroy(WorkExperience $workExperience)
    {
        abort_if(Gate::denies('work_experience_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workExperience->delete();

        return back();
    }

    public function massDestroy(MassDestroyWorkExperienceRequest $request)
    {
        WorkExperience::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
