<?php

namespace App\Http\Controllers\Frontend;

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

class ProfilesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('profile_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $profiles = Profile::with(['user', 'marital_status', 'disability_type', 'religion', 'category', 'caste', 'nationality', 'district_of_birth', 'state_of_birth', 'domicile_state', 'domicile_district'])->get();

        return view('frontend.profiles.index', compact('profiles'));
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

        return view('frontend.profiles.create', compact('castes', 'categories', 'disability_types', 'district_of_births', 'domicile_districts', 'domicile_states', 'marital_statuses', 'nationalities', 'religions', 'state_of_births', 'users'));
    }

    public function store(StoreProfileRequest $request)
    {
        $profile = Profile::create($request->all());

        return redirect()->route('frontend.profiles.index');
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

        return view('frontend.profiles.edit', compact('castes', 'categories', 'disability_types', 'district_of_births', 'domicile_districts', 'domicile_states', 'marital_statuses', 'nationalities', 'profile', 'religions', 'state_of_births', 'users'));
    }

    public function update(UpdateProfileRequest $request, Profile $profile)
    {
        $profile->update($request->all());

        return redirect()->route('frontend.profiles.index');
    }

    public function show(Profile $profile)
    {
        abort_if(Gate::denies('profile_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $profile->load('user', 'marital_status', 'disability_type', 'religion', 'category', 'caste', 'nationality', 'district_of_birth', 'state_of_birth', 'domicile_state', 'domicile_district');

        return view('frontend.profiles.show', compact('profile'));
    }

    public function destroy(Profile $profile)
    {
        abort_if(Gate::denies('profile_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $profile->delete();

        return back();
    }

    public function massDestroy(MassDestroyProfileRequest $request)
    {
        Profile::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
