<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProfileRequest;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Caste;
use App\Models\Category;
use App\Models\Country;
use App\Models\DisabilityType;
use App\Models\MaritalStatus;
use App\Models\PostalCode;
use App\Models\Profile;
use App\Models\Province;
use App\Models\Religion;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProfilesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('profile_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Profile::with(['user', 'marital_status', 'disability_type', 'religion', 'category', 'caste', 'nationality', 'district_of_birth', 'state_of_birth', 'domicile_state', 'domicile_district'])->select(sprintf('%s.*', (new Profile)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'profile_show';
                $editGate      = 'profile_edit';
                $deleteGate    = 'profile_delete';
                $crudRoutePart = 'profiles';

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

            $table->editColumn('first_name', function ($row) {
                return $row->first_name ? $row->first_name : '';
            });
            $table->editColumn('middle_name', function ($row) {
                return $row->middle_name ? $row->middle_name : '';
            });
            $table->editColumn('last_name', function ($row) {
                return $row->last_name ? $row->last_name : '';
            });
            $table->editColumn('fathers_name', function ($row) {
                return $row->fathers_name ? $row->fathers_name : '';
            });

            $table->editColumn('gender', function ($row) {
                return $row->gender ? Profile::GENDER_SELECT[$row->gender] : '';
            });
            $table->editColumn('mobile', function ($row) {
                return $row->mobile ? $row->mobile : '';
            });
            $table->editColumn('pwd', function ($row) {
                return $row->pwd ? Profile::PWD_SELECT[$row->pwd] : '';
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

            $table->addColumn('nationality_name', function ($row) {
                return $row->nationality ? $row->nationality->name : '';
            });

            $table->editColumn('remarks', function ($row) {
                return $row->remarks ? $row->remarks : '';
            });
            $table->editColumn('verified', function ($row) {
                return $row->verified ? $row->verified : '';
            });
            $table->editColumn('locked', function ($row) {
                return $row->locked ? $row->locked : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user', 'religion', 'category', 'caste', 'nationality']);

            return $table->make(true);
        }

        return view('admin.profiles.index');
    }

    public function create()
    {
        abort_if(Gate::denies('profile_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $marital_statuses = MaritalStatus::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $disability_types = DisabilityType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $religions = Religion::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $castes = Caste::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $nationalities = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $district_of_births = PostalCode::pluck('district', 'id')->prepend(trans('global.pleaseSelect'), '');

        $state_of_births = Province::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $domicile_states = Province::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $domicile_districts = PostalCode::pluck('district', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.profiles.create', compact('castes', 'categories', 'disability_types', 'district_of_births', 'domicile_districts', 'domicile_states', 'marital_statuses', 'nationalities', 'religions', 'state_of_births', 'users'));
    }

    public function store(StoreProfileRequest $request)
    {
        $profile = Profile::create($request->all());

        return redirect()->route('admin.profiles.index');
    }

    public function edit(Profile $profile)
    {
        abort_if(Gate::denies('profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $marital_statuses = MaritalStatus::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $disability_types = DisabilityType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $religions = Religion::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $castes = Caste::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $nationalities = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $district_of_births = PostalCode::pluck('district', 'id')->prepend(trans('global.pleaseSelect'), '');

        $state_of_births = Province::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $domicile_states = Province::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $domicile_districts = PostalCode::pluck('district', 'id')->prepend(trans('global.pleaseSelect'), '');

        $profile->load('user', 'marital_status', 'disability_type', 'religion', 'category', 'caste', 'nationality', 'district_of_birth', 'state_of_birth', 'domicile_state', 'domicile_district');

        return view('admin.profiles.edit', compact('castes', 'categories', 'disability_types', 'district_of_births', 'domicile_districts', 'domicile_states', 'marital_statuses', 'nationalities', 'profile', 'religions', 'state_of_births', 'users'));
    }

    public function update(UpdateProfileRequest $request, Profile $profile)
    {
        $profile->update($request->all());

        return redirect()->route('admin.profiles.index');
    }

    public function show(Profile $profile)
    {
        abort_if(Gate::denies('profile_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $profile->load('user', 'marital_status', 'disability_type', 'religion', 'category', 'caste', 'nationality', 'district_of_birth', 'state_of_birth', 'domicile_state', 'domicile_district');

        return view('admin.profiles.show', compact('profile'));
    }

    public function destroy(Profile $profile)
    {
        abort_if(Gate::denies('profile_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $profile->delete();

        return back();
    }

    public function massDestroy(MassDestroyProfileRequest $request)
    {
        $profiles = Profile::find(request('ids'));

        foreach ($profiles as $profile) {
            $profile->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
