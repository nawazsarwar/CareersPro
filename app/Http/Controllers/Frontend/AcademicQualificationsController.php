<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAcademicQualificationRequest;
use App\Http\Requests\StoreAcademicQualificationRequest;
use App\Http\Requests\UpdateAcademicQualificationRequest;
use App\Models\AcademicQualification;
use App\Models\Board;
use App\Models\QualificationLevel;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class AcademicQualificationsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('academic_qualification_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $academicQualifications = AcademicQualification::with(['name', 'board', 'user', 'media'])->get();

        $qualification_levels = QualificationLevel::get();

        $boards = Board::get();

        $users = User::get();

        return view('frontend.academicQualifications.index', compact('academicQualifications', 'boards', 'qualification_levels', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('academic_qualification_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = QualificationLevel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $boards = Board::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.academicQualifications.create', compact('boards', 'names', 'users'));
    }

    public function store(StoreAcademicQualificationRequest $request)
    {
        $academicQualification = AcademicQualification::create($request->all());

        if ($request->input('document', false)) {
            $academicQualification->addMedia(storage_path('tmp/uploads/' . basename($request->input('document'))))->toMediaCollection('document');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $academicQualification->id]);
        }

        return redirect()->route('frontend.academic-qualifications.index');
    }

    public function edit(AcademicQualification $academicQualification)
    {
        abort_if(Gate::denies('academic_qualification_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = QualificationLevel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $boards = Board::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $academicQualification->load('name', 'board', 'user');

        return view('frontend.academicQualifications.edit', compact('academicQualification', 'boards', 'names', 'users'));
    }

    public function update(UpdateAcademicQualificationRequest $request, AcademicQualification $academicQualification)
    {
        $academicQualification->update($request->all());

        if ($request->input('document', false)) {
            if (! $academicQualification->document || $request->input('document') !== $academicQualification->document->file_name) {
                if ($academicQualification->document) {
                    $academicQualification->document->delete();
                }
                $academicQualification->addMedia(storage_path('tmp/uploads/' . basename($request->input('document'))))->toMediaCollection('document');
            }
        } elseif ($academicQualification->document) {
            $academicQualification->document->delete();
        }

        return redirect()->route('frontend.academic-qualifications.index');
    }

    public function show(AcademicQualification $academicQualification)
    {
        abort_if(Gate::denies('academic_qualification_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $academicQualification->load('name', 'board', 'user');

        return view('frontend.academicQualifications.show', compact('academicQualification'));
    }

    public function destroy(AcademicQualification $academicQualification)
    {
        abort_if(Gate::denies('academic_qualification_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $academicQualification->delete();

        return back();
    }

    public function massDestroy(MassDestroyAcademicQualificationRequest $request)
    {
        $academicQualifications = AcademicQualification::find(request('ids'));

        foreach ($academicQualifications as $academicQualification) {
            $academicQualification->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('academic_qualification_create') && Gate::denies('academic_qualification_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new AcademicQualification();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
