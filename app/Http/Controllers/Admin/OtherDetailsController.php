<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyOtherDetailRequest;
use App\Http\Requests\StoreOtherDetailRequest;
use App\Http\Requests\UpdateOtherDetailRequest;
use App\Models\OtherDetail;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class OtherDetailsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('other_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = OtherDetail::with(['user'])->select(sprintf('%s.*', (new OtherDetail)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'other_detail_show';
                $editGate      = 'other_detail_edit';
                $deleteGate    = 'other_detail_delete';
                $crudRoutePart = 'other-details';

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
            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->editColumn('fellowship_undergraduate', function ($row) {
                return $row->fellowship_undergraduate ? $row->fellowship_undergraduate : '';
            });
            $table->editColumn('fellowship_graduate', function ($row) {
                return $row->fellowship_graduate ? $row->fellowship_graduate : '';
            });
            $table->editColumn('fellowship_postgraduate', function ($row) {
                return $row->fellowship_postgraduate ? $row->fellowship_postgraduate : '';
            });
            $table->editColumn('phd_thesis_title', function ($row) {
                return $row->phd_thesis_title ? $row->phd_thesis_title : '';
            });
            $table->editColumn('research_phd_awarded', function ($row) {
                return $row->research_phd_awarded ? $row->research_phd_awarded : '';
            });
            $table->editColumn('research_phd_thesis', function ($row) {
                return $row->research_phd_thesis ? $row->research_phd_thesis : '';
            });
            $table->editColumn('research_phd_total_scholars', function ($row) {
                return $row->research_phd_total_scholars ? $row->research_phd_total_scholars : '';
            });
            $table->editColumn('research_mphil_awarded', function ($row) {
                return $row->research_mphil_awarded ? $row->research_mphil_awarded : '';
            });
            $table->editColumn('research_mphil_thesis', function ($row) {
                return $row->research_mphil_thesis ? $row->research_mphil_thesis : '';
            });
            $table->editColumn('research_mphil_total_scholars', function ($row) {
                return $row->research_mphil_total_scholars ? $row->research_mphil_total_scholars : '';
            });
            $table->editColumn('research_other_awarded', function ($row) {
                return $row->research_other_awarded ? $row->research_other_awarded : '';
            });
            $table->editColumn('research_other_thesis', function ($row) {
                return $row->research_other_thesis ? $row->research_other_thesis : '';
            });
            $table->editColumn('research_other_total_scholars', function ($row) {
                return $row->research_other_total_scholars ? $row->research_other_total_scholars : '';
            });
            $table->editColumn('eminent_scholar', function ($row) {
                return $row->eminent_scholar ? $row->eminent_scholar : '';
            });
            $table->editColumn('contribution_to_knowledge', function ($row) {
                return $row->contribution_to_knowledge ? $row->contribution_to_knowledge : '';
            });
            $table->editColumn('engaged_in_research', function ($row) {
                return $row->engaged_in_research ? $row->engaged_in_research : '';
            });
            $table->editColumn('industry_experience', function ($row) {
                return $row->industry_experience ? $row->industry_experience : '';
            });
            $table->editColumn('current_pay_level', function ($row) {
                return $row->current_pay_level ? $row->current_pay_level : '';
            });
            $table->editColumn('current_pay_range', function ($row) {
                return $row->current_pay_range ? $row->current_pay_range : '';
            });
            $table->editColumn('current_basic_pay', function ($row) {
                return $row->current_basic_pay ? $row->current_basic_pay : '';
            });
            $table->editColumn('current_pay_band', function ($row) {
                return $row->current_pay_band ? $row->current_pay_band : '';
            });
            $table->editColumn('current_grade_pay', function ($row) {
                return $row->current_grade_pay ? $row->current_grade_pay : '';
            });
            $table->editColumn('current_basic_pay_old', function ($row) {
                return $row->current_basic_pay_old ? $row->current_basic_pay_old : '';
            });
            $table->editColumn('current_allowances', function ($row) {
                return $row->current_allowances ? $row->current_allowances : '';
            });
            $table->editColumn('current_allowances_total', function ($row) {
                return $row->current_allowances_total ? $row->current_allowances_total : '';
            });

            $table->editColumn('minimum_initial_pay', function ($row) {
                return $row->minimum_initial_pay ? $row->minimum_initial_pay : '';
            });
            $table->editColumn('joining_time', function ($row) {
                return $row->joining_time ? $row->joining_time : '';
            });
            $table->editColumn('books_published', function ($row) {
                return $row->books_published ? $row->books_published : '';
            });
            $table->editColumn('books_accepted', function ($row) {
                return $row->books_accepted ? $row->books_accepted : '';
            });
            $table->editColumn('papers_published', function ($row) {
                return $row->papers_published ? $row->papers_published : '';
            });
            $table->editColumn('papers_accepted', function ($row) {
                return $row->papers_accepted ? $row->papers_accepted : '';
            });
            $table->editColumn('articles_published', function ($row) {
                return $row->articles_published ? $row->articles_published : '';
            });
            $table->editColumn('articles_accepted', function ($row) {
                return $row->articles_accepted ? $row->articles_accepted : '';
            });
            $table->editColumn('papers_read_published', function ($row) {
                return $row->papers_read_published ? $row->papers_read_published : '';
            });
            $table->editColumn('papers_read_accepted', function ($row) {
                return $row->papers_read_accepted ? $row->papers_read_accepted : '';
            });
            $table->editColumn('eca_university_administration', function ($row) {
                return $row->eca_university_administration ? $row->eca_university_administration : '';
            });
            $table->editColumn('eca_student', function ($row) {
                return $row->eca_student ? $row->eca_student : '';
            });
            $table->editColumn('eca_residential_student', function ($row) {
                return $row->eca_residential_student ? $row->eca_residential_student : '';
            });
            $table->editColumn('eca_cultural', function ($row) {
                return $row->eca_cultural ? $row->eca_cultural : '';
            });
            $table->editColumn('relevant_work', function ($row) {
                return $row->relevant_work ? $row->relevant_work : '';
            });
            $table->editColumn('previous_applications', function ($row) {
                return $row->previous_applications ? $row->previous_applications : '';
            });
            $table->editColumn('testimonial_1', function ($row) {
                return $row->testimonial_1 ? $row->testimonial_1 : '';
            });
            $table->editColumn('testimonial_2', function ($row) {
                return $row->testimonial_2 ? $row->testimonial_2 : '';
            });
            $table->editColumn('testimonial_3', function ($row) {
                return $row->testimonial_3 ? $row->testimonial_3 : '';
            });
            $table->editColumn('remark_essential_qualification', function ($row) {
                return $row->remark_essential_qualification ? $row->remark_essential_qualification : '';
            });
            $table->editColumn('remark_essential_qualification_document', function ($row) {
                return $row->remark_essential_qualification_document ? '<a href="' . $row->remark_essential_qualification_document->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('remark_desirable_qualification', function ($row) {
                return $row->remark_desirable_qualification ? $row->remark_desirable_qualification : '';
            });
            $table->editColumn('remark_desirable_qualification_document', function ($row) {
                return $row->remark_desirable_qualification_document ? '<a href="' . $row->remark_desirable_qualification_document->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user', 'remark_essential_qualification_document', 'remark_desirable_qualification_document']);

            return $table->make(true);
        }

        $users = User::get();

        return view('admin.otherDetails.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('other_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.otherDetails.create', compact('users'));
    }

    public function store(StoreOtherDetailRequest $request)
    {
        $otherDetail = OtherDetail::create($request->all());

        if ($request->input('remark_essential_qualification_document', false)) {
            $otherDetail->addMedia(storage_path('tmp/uploads/' . basename($request->input('remark_essential_qualification_document'))))->toMediaCollection('remark_essential_qualification_document');
        }

        if ($request->input('remark_desirable_qualification_document', false)) {
            $otherDetail->addMedia(storage_path('tmp/uploads/' . basename($request->input('remark_desirable_qualification_document'))))->toMediaCollection('remark_desirable_qualification_document');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $otherDetail->id]);
        }

        return redirect()->route('admin.other-details.index');
    }

    public function edit(OtherDetail $otherDetail)
    {
        abort_if(Gate::denies('other_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $otherDetail->load('user');

        return view('admin.otherDetails.edit', compact('otherDetail', 'users'));
    }

    public function update(UpdateOtherDetailRequest $request, OtherDetail $otherDetail)
    {
        $otherDetail->update($request->all());

        if ($request->input('remark_essential_qualification_document', false)) {
            if (! $otherDetail->remark_essential_qualification_document || $request->input('remark_essential_qualification_document') !== $otherDetail->remark_essential_qualification_document->file_name) {
                if ($otherDetail->remark_essential_qualification_document) {
                    $otherDetail->remark_essential_qualification_document->delete();
                }
                $otherDetail->addMedia(storage_path('tmp/uploads/' . basename($request->input('remark_essential_qualification_document'))))->toMediaCollection('remark_essential_qualification_document');
            }
        } elseif ($otherDetail->remark_essential_qualification_document) {
            $otherDetail->remark_essential_qualification_document->delete();
        }

        if ($request->input('remark_desirable_qualification_document', false)) {
            if (! $otherDetail->remark_desirable_qualification_document || $request->input('remark_desirable_qualification_document') !== $otherDetail->remark_desirable_qualification_document->file_name) {
                if ($otherDetail->remark_desirable_qualification_document) {
                    $otherDetail->remark_desirable_qualification_document->delete();
                }
                $otherDetail->addMedia(storage_path('tmp/uploads/' . basename($request->input('remark_desirable_qualification_document'))))->toMediaCollection('remark_desirable_qualification_document');
            }
        } elseif ($otherDetail->remark_desirable_qualification_document) {
            $otherDetail->remark_desirable_qualification_document->delete();
        }

        return redirect()->route('admin.other-details.index');
    }

    public function show(OtherDetail $otherDetail)
    {
        abort_if(Gate::denies('other_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $otherDetail->load('user');

        return view('admin.otherDetails.show', compact('otherDetail'));
    }

    public function destroy(OtherDetail $otherDetail)
    {
        abort_if(Gate::denies('other_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $otherDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroyOtherDetailRequest $request)
    {
        $otherDetails = OtherDetail::find(request('ids'));

        foreach ($otherDetails as $otherDetail) {
            $otherDetail->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('other_detail_create') && Gate::denies('other_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new OtherDetail();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
