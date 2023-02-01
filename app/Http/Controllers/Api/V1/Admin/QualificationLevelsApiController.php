<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQualificationLevelRequest;
use App\Http\Requests\UpdateQualificationLevelRequest;
use App\Http\Resources\Admin\QualificationLevelResource;
use App\Models\QualificationLevel;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QualificationLevelsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('qualification_level_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new QualificationLevelResource(QualificationLevel::all());
    }

    public function store(StoreQualificationLevelRequest $request)
    {
        $qualificationLevel = QualificationLevel::create($request->all());

        return (new QualificationLevelResource($qualificationLevel))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(QualificationLevel $qualificationLevel)
    {
        abort_if(Gate::denies('qualification_level_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new QualificationLevelResource($qualificationLevel);
    }

    public function update(UpdateQualificationLevelRequest $request, QualificationLevel $qualificationLevel)
    {
        $qualificationLevel->update($request->all());

        return (new QualificationLevelResource($qualificationLevel))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(QualificationLevel $qualificationLevel)
    {
        abort_if(Gate::denies('qualification_level_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $qualificationLevel->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
