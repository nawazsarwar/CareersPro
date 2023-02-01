<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreAcademicQualificationRequest;
use App\Http\Requests\UpdateAcademicQualificationRequest;
use App\Http\Resources\Admin\AcademicQualificationResource;
use App\Models\AcademicQualification;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AcademicQualificationsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('academic_qualification_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AcademicQualificationResource(AcademicQualification::with(['name', 'board', 'user'])->get());
    }

    public function store(StoreAcademicQualificationRequest $request)
    {
        $academicQualification = AcademicQualification::create($request->all());

        if ($request->input('document', false)) {
            $academicQualification->addMedia(storage_path('tmp/uploads/' . basename($request->input('document'))))->toMediaCollection('document');
        }

        return (new AcademicQualificationResource($academicQualification))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AcademicQualification $academicQualification)
    {
        abort_if(Gate::denies('academic_qualification_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AcademicQualificationResource($academicQualification->load(['name', 'board', 'user']));
    }

    public function update(UpdateAcademicQualificationRequest $request, AcademicQualification $academicQualification)
    {
        $academicQualification->update($request->all());

        if ($request->input('document', false)) {
            if (!$academicQualification->document || $request->input('document') !== $academicQualification->document->file_name) {
                if ($academicQualification->document) {
                    $academicQualification->document->delete();
                }
                $academicQualification->addMedia(storage_path('tmp/uploads/' . basename($request->input('document'))))->toMediaCollection('document');
            }
        } elseif ($academicQualification->document) {
            $academicQualification->document->delete();
        }

        return (new AcademicQualificationResource($academicQualification))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(AcademicQualification $academicQualification)
    {
        abort_if(Gate::denies('academic_qualification_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $academicQualification->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
