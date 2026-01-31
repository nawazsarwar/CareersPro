<?php

namespace App\Http\Controllers\Frontend;

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

class ApplicationFormsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('application_form_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationForms = ApplicationForm::with(['user', 'advertisement', 'post', 'disability_type', 'religion', 'category', 'caste', 'nationality', 'domicile_state', 'scrutinized_by', 'eligibility_updated_by'])->get();

        $users = User::get();

        $advertisements = Advertisement::get();

        $posts = Post::get();

        $disability_types = DisabilityType::get();

        $religions = Religion::get();

        $categories = Category::get();

        $castes = Caste::get();

        $countries = Country::get();

        $provinces = Province::get();

        return view('frontend.applicationForms.index', compact('advertisements', 'applicationForms', 'castes', 'categories', 'countries', 'disability_types', 'posts', 'provinces', 'religions', 'users'));
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

        return view('frontend.applicationForms.create', compact('advertisements', 'castes', 'categories', 'disability_types', 'domicile_states', 'eligibility_updated_bies', 'nationalities', 'posts', 'religions', 'scrutinized_bies', 'users'));
    }

    public function store(StoreApplicationFormRequest $request)
    {
        $applicationForm = ApplicationForm::create($request->all());

        return redirect()->route('frontend.application-forms.index');
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

        return view('frontend.applicationForms.edit', compact('advertisements', 'applicationForm', 'castes', 'categories', 'disability_types', 'domicile_states', 'eligibility_updated_bies', 'nationalities', 'posts', 'religions', 'scrutinized_bies', 'users'));
    }

    public function update(UpdateApplicationFormRequest $request, ApplicationForm $applicationForm)
    {
        $applicationForm->update($request->all());

        return redirect()->route('frontend.application-forms.index');
    }

    public function show(ApplicationForm $applicationForm)
    {
        abort_if(Gate::denies('application_form_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationForm->load('user', 'advertisement', 'post', 'disability_type', 'religion', 'category', 'caste', 'nationality', 'domicile_state', 'scrutinized_by', 'eligibility_updated_by');

        return view('frontend.applicationForms.show', compact('applicationForm'));
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
