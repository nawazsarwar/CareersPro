<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyQualificationLevelRequest;
use App\Http\Requests\StoreQualificationLevelRequest;
use App\Http\Requests\UpdateQualificationLevelRequest;
use App\Models\QualificationLevel;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QualificationLevelsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('qualification_level_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $qualificationLevels = QualificationLevel::all();

        return view('frontend.qualificationLevels.index', compact('qualificationLevels'));
    }

    public function create()
    {
        abort_if(Gate::denies('qualification_level_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.qualificationLevels.create');
    }

    public function store(StoreQualificationLevelRequest $request)
    {
        $qualificationLevel = QualificationLevel::create($request->all());

        return redirect()->route('frontend.qualification-levels.index');
    }

    public function edit(QualificationLevel $qualificationLevel)
    {
        abort_if(Gate::denies('qualification_level_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.qualificationLevels.edit', compact('qualificationLevel'));
    }

    public function update(UpdateQualificationLevelRequest $request, QualificationLevel $qualificationLevel)
    {
        $qualificationLevel->update($request->all());

        return redirect()->route('frontend.qualification-levels.index');
    }

    public function show(QualificationLevel $qualificationLevel)
    {
        abort_if(Gate::denies('qualification_level_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.qualificationLevels.show', compact('qualificationLevel'));
    }

    public function destroy(QualificationLevel $qualificationLevel)
    {
        abort_if(Gate::denies('qualification_level_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $qualificationLevel->delete();

        return back();
    }

    public function massDestroy(MassDestroyQualificationLevelRequest $request)
    {
        QualificationLevel::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
