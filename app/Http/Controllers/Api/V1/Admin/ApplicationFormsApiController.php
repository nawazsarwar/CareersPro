<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApplicationFormRequest;
use App\Http\Requests\UpdateApplicationFormRequest;
use App\Http\Resources\Admin\ApplicationFormResource;
use App\Models\ApplicationForm;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplicationFormsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('application_form_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ApplicationFormResource(ApplicationForm::with(['user', 'advertisement', 'post', 'disability_type', 'religion', 'category', 'caste', 'nationality', 'domicile_state', 'scrutinized_by', 'eligibility_updated_by'])->get());
    }

    public function store(StoreApplicationFormRequest $request)
    {
        $applicationForm = ApplicationForm::create($request->all());

        return (new ApplicationFormResource($applicationForm))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ApplicationForm $applicationForm)
    {
        abort_if(Gate::denies('application_form_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ApplicationFormResource($applicationForm->load(['user', 'advertisement', 'post', 'disability_type', 'religion', 'category', 'caste', 'nationality', 'domicile_state', 'scrutinized_by', 'eligibility_updated_by']));
    }

    public function update(UpdateApplicationFormRequest $request, ApplicationForm $applicationForm)
    {
        $applicationForm->update($request->all());

        return (new ApplicationFormResource($applicationForm))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ApplicationForm $applicationForm)
    {
        abort_if(Gate::denies('application_form_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationForm->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
