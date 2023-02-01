<?php

namespace App\Http\Controllers\Admin;

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

        return view('admin.workExperiences.index', compact('workExperiences'));
    }

    public function create()
    {
        abort_if(Gate::denies('work_experience_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.workExperiences.create');
    }

    public function store(StoreWorkExperienceRequest $request)
    {
        $workExperience = WorkExperience::create($request->all());

        return redirect()->route('admin.work-experiences.index');
    }

    public function edit(WorkExperience $workExperience)
    {
        abort_if(Gate::denies('work_experience_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.workExperiences.edit', compact('workExperience'));
    }

    public function update(UpdateWorkExperienceRequest $request, WorkExperience $workExperience)
    {
        $workExperience->update($request->all());

        return redirect()->route('admin.work-experiences.index');
    }

    public function show(WorkExperience $workExperience)
    {
        abort_if(Gate::denies('work_experience_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.workExperiences.show', compact('workExperience'));
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
