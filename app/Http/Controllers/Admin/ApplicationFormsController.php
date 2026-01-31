<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyApplicationFormRequest;
use App\Http\Requests\StoreApplicationFormRequest;
use App\Http\Requests\UpdateApplicationFormRequest;
use App\Models\Advertisement;
use App\Models\ApplicationForm;
use App\Models\Caste;
use App\Models\Category;
use App\Models\Country;
use App\Models\DisabilityType;
use App\Models\Post;
use App\Models\Province;
use App\Models\Religion;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ApplicationFormsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('application_form_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ApplicationForm::with(['user', 'advertisement', 'post', 'disability_type', 'religion', 'category', 'caste', 'nationality', 'domicile_state', 'scrutinized_by', 'eligibility_updated_by'])->select(sprintf('%s.*', (new ApplicationForm)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'application_form_show';
                $editGate      = 'application_form_edit';
                $deleteGate    = 'application_form_delete';
                $crudRoutePart = 'application-forms';

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

            $table->editColumn('roll_no', function ($row) {
                return $row->roll_no ? $row->roll_no : '';
            });
            $table->editColumn('random_no', function ($row) {
                return $row->random_no ? $row->random_no : '';
            });
            $table->addColumn('advertisement_title', function ($row) {
                return $row->advertisement ? $row->advertisement->title : '';
            });

            $table->editColumn('advertisement_title', function ($row) {
                return $row->advertisement_title ? $row->advertisement_title : '';
            });
            $table->addColumn('post_title', function ($row) {
                return $row->post ? $row->post->title : '';
            });

            $table->editColumn('post_serial_no', function ($row) {
                return $row->post_serial_no ? $row->post_serial_no : '';
            });
            $table->editColumn('post_title', function ($row) {
                return $row->post_title ? $row->post_title : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('gender', function ($row) {
                return $row->gender ? ApplicationForm::GENDER_SELECT[$row->gender] : '';
            });

            $table->editColumn('mobile', function ($row) {
                return $row->mobile ? $row->mobile : '';
            });
            $table->editColumn('disability', function ($row) {
                return $row->disability ? ApplicationForm::DISABILITY_SELECT[$row->disability] : '';
            });
            $table->addColumn('disability_type_name', function ($row) {
                return $row->disability_type ? $row->disability_type->name : '';
            });

            $table->editColumn('disability_percent', function ($row) {
                return $row->disability_percent ? $row->disability_percent : '';
            });
            $table->editColumn('aadhaar_no', function ($row) {
                return $row->aadhaar_no ? $row->aadhaar_no : '';
            });
            $table->addColumn('religion_name', function ($row) {
                return $row->religion ? $row->religion->name : '';
            });

            $table->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : '';
            });

            $table->addColumn('caste_name', function ($row) {
                return $row->caste ? $row->caste->name : '';
            });

            $table->editColumn('sub_caste', function ($row) {
                return $row->sub_caste ? $row->sub_caste : '';
            });
            $table->addColumn('nationality_name', function ($row) {
                return $row->nationality ? $row->nationality->name : '';
            });

            $table->editColumn('permanent_address', function ($row) {
                return $row->permanent_address ? $row->permanent_address : '';
            });
            $table->editColumn('correspondence_address', function ($row) {
                return $row->correspondence_address ? $row->correspondence_address : '';
            });
            $table->editColumn('domicile_district', function ($row) {
                return $row->domicile_district ? $row->domicile_district : '';
            });
            $table->addColumn('domicile_state_name', function ($row) {
                return $row->domicile_state ? $row->domicile_state->name : '';
            });

            $table->editColumn('basic_details', function ($row) {
                return $row->basic_details ? $row->basic_details : '';
            });
            $table->editColumn('additional_details', function ($row) {
                return $row->additional_details ? $row->additional_details : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? $row->status : '';
            });
            $table->editColumn('remarks', function ($row) {
                return $row->remarks ? $row->remarks : '';
            });
            $table->editColumn('review', function ($row) {
                return $row->review ? $row->review : '';
            });
            $table->editColumn('submitted', function ($row) {
                return $row->submitted ? $row->submitted : '';
            });
            $table->editColumn('paid', function ($row) {
                return $row->paid ? $row->paid : '';
            });
            $table->editColumn('hardcopy_received', function ($row) {
                return $row->hardcopy_received ? ApplicationForm::HARDCOPY_RECEIVED_SELECT[$row->hardcopy_received] : '';
            });
            $table->editColumn('scrutinized', function ($row) {
                return $row->scrutinized ? ApplicationForm::SCRUTINIZED_SELECT[$row->scrutinized] : '';
            });
            $table->addColumn('scrutinized_by_name', function ($row) {
                return $row->scrutinized_by ? $row->scrutinized_by->name : '';
            });

            $table->editColumn('scrutiny_remark', function ($row) {
                return $row->scrutiny_remark ? $row->scrutiny_remark : '';
            });
            $table->editColumn('eligible', function ($row) {
                return $row->eligible ? ApplicationForm::ELIGIBLE_SELECT[$row->eligible] : '';
            });
            $table->editColumn('eligibility_remark', function ($row) {
                return $row->eligibility_remark ? $row->eligibility_remark : '';
            });
            $table->addColumn('eligibility_updated_by_name', function ($row) {
                return $row->eligibility_updated_by ? $row->eligibility_updated_by->name : '';
            });

            $table->editColumn('order_uid', function ($row) {
                return $row->order_uid ? $row->order_uid : '';
            });

            $table->editColumn('centre_name', function ($row) {
                return $row->centre_name ? $row->centre_name : '';
            });
            $table->editColumn('centre_code', function ($row) {
                return $row->centre_code ? $row->centre_code : '';
            });
            $table->editColumn('centre_address', function ($row) {
                return $row->centre_address ? $row->centre_address : '';
            });
            $table->editColumn('centre_city', function ($row) {
                return $row->centre_city ? $row->centre_city : '';
            });
            $table->editColumn('room_no', function ($row) {
                return $row->room_no ? $row->room_no : '';
            });
            $table->editColumn('seat_no', function ($row) {
                return $row->seat_no ? $row->seat_no : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user', 'advertisement', 'post', 'disability_type', 'religion', 'category', 'caste', 'nationality', 'domicile_state', 'scrutinized_by', 'eligibility_updated_by']);

            return $table->make(true);
        }

        $users            = User::get();
        $advertisements   = Advertisement::get();
        $posts            = Post::get();
        $disability_types = DisabilityType::get();
        $religions        = Religion::get();
        $categories       = Category::get();
        $castes           = Caste::get();
        $countries        = Country::get();
        $provinces        = Province::get();

        return view('admin.applicationForms.index', compact('users', 'advertisements', 'posts', 'disability_types', 'religions', 'categories', 'castes', 'countries', 'provinces'));
    }

    public function create()
    {
        abort_if(Gate::denies('application_form_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $advertisements = Advertisement::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $posts = Post::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $disability_types = DisabilityType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $religions = Religion::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $castes = Caste::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $nationalities = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $domicile_states = Province::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $scrutinized_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $eligibility_updated_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.applicationForms.create', compact('advertisements', 'castes', 'categories', 'disability_types', 'domicile_states', 'eligibility_updated_bies', 'nationalities', 'posts', 'religions', 'scrutinized_bies', 'users'));
    }

    public function store(StoreApplicationFormRequest $request)
    {
        $applicationForm = ApplicationForm::create($request->all());

        return redirect()->route('admin.application-forms.index');
    }

    public function edit(ApplicationForm $applicationForm)
    {
        abort_if(Gate::denies('application_form_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $advertisements = Advertisement::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $posts = Post::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $disability_types = DisabilityType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $religions = Religion::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $castes = Caste::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $nationalities = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $domicile_states = Province::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $scrutinized_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $eligibility_updated_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $applicationForm->load('user', 'advertisement', 'post', 'disability_type', 'religion', 'category', 'caste', 'nationality', 'domicile_state', 'scrutinized_by', 'eligibility_updated_by');

        return view('admin.applicationForms.edit', compact('advertisements', 'applicationForm', 'castes', 'categories', 'disability_types', 'domicile_states', 'eligibility_updated_bies', 'nationalities', 'posts', 'religions', 'scrutinized_bies', 'users'));
    }

    public function update(UpdateApplicationFormRequest $request, ApplicationForm $applicationForm)
    {
        $applicationForm->update($request->all());

        return redirect()->route('admin.application-forms.index');
    }

    public function show(ApplicationForm $applicationForm)
    {
        abort_if(Gate::denies('application_form_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationForm->load('user', 'advertisement', 'post', 'disability_type', 'religion', 'category', 'caste', 'nationality', 'domicile_state', 'scrutinized_by', 'eligibility_updated_by');

        return view('admin.applicationForms.show', compact('applicationForm'));
    }

    public function destroy(ApplicationForm $applicationForm)
    {
        abort_if(Gate::denies('application_form_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationForm->delete();

        return back();
    }

    public function massDestroy(MassDestroyApplicationFormRequest $request)
    {
        $applicationForms = ApplicationForm::find(request('ids'));

        foreach ($applicationForms as $applicationForm) {
            $applicationForm->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
