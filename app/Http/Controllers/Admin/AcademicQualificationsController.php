<?php

namespace App\Http\Controllers\Admin;

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
use Yajra\DataTables\Facades\DataTables;

class AcademicQualificationsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('academic_qualification_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AcademicQualification::with(['name', 'board', 'user'])->select(sprintf('%s.*', (new AcademicQualification)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'academic_qualification_show';
                $editGate      = 'academic_qualification_edit';
                $deleteGate    = 'academic_qualification_delete';
                $crudRoutePart = 'academic-qualifications';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('name_name', function ($row) {
                return $row->name ? $row->name->name : '';
            });

            $table->editColumn('course', function ($row) {
                return $row->course ? $row->course : '';
            });
            $table->addColumn('board_name', function ($row) {
                return $row->board ? $row->board->name : '';
            });

            $table->editColumn('division', function ($row) {
                return $row->division ? AcademicQualification::DIVISION_SELECT[$row->division] : '';
            });
            $table->editColumn('percentage', function ($row) {
                return $row->percentage ? $row->percentage : '';
            });
            $table->editColumn('cgpa', function ($row) {
                return $row->cgpa ? $row->cgpa : '';
            });
            $table->editColumn('subjects', function ($row) {
                return $row->subjects ? $row->subjects : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('remarks', function ($row) {
                return $row->remarks ? $row->remarks : '';
            });
            $table->editColumn('document', function ($row) {
                return $row->document ? '<a href="' . $row->document->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'name', 'board', 'document', 'user']);

            return $table->make(true);
        }

        $qualification_levels = QualificationLevel::get();
        $boards               = Board::get();
        $users                = User::get();

        return view('admin.academicQualifications.index', compact('qualification_levels', 'boards', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('academic_qualification_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = QualificationLevel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $boards = Board::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.academicQualifications.create', compact('boards', 'names', 'users'));
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

        return redirect()->route('admin.academic-qualifications.index');
    }

    public function edit(AcademicQualification $academicQualification)
    {
        abort_if(Gate::denies('academic_qualification_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = QualificationLevel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $boards = Board::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $academicQualification->load('name', 'board', 'user');

        return view('admin.academicQualifications.edit', compact('academicQualification', 'boards', 'names', 'users'));
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

        return redirect()->route('admin.academic-qualifications.index');
    }

    public function show(AcademicQualification $academicQualification)
    {
        abort_if(Gate::denies('academic_qualification_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $academicQualification->load('name', 'board', 'user');

        return view('admin.academicQualifications.show', compact('academicQualification'));
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
