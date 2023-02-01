<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEligibilityTestRequest;
use App\Http\Requests\UpdateEligibilityTestRequest;
use App\Http\Resources\Admin\EligibilityTestResource;
use App\Models\EligibilityTest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EligibilityTestsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('eligibility_test_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EligibilityTestResource(EligibilityTest::with(['user'])->get());
    }

    public function store(StoreEligibilityTestRequest $request)
    {
        $eligibilityTest = EligibilityTest::create($request->all());

        return (new EligibilityTestResource($eligibilityTest))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(EligibilityTest $eligibilityTest)
    {
        abort_if(Gate::denies('eligibility_test_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EligibilityTestResource($eligibilityTest->load(['user']));
    }

    public function update(UpdateEligibilityTestRequest $request, EligibilityTest $eligibilityTest)
    {
        $eligibilityTest->update($request->all());

        return (new EligibilityTestResource($eligibilityTest))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(EligibilityTest $eligibilityTest)
    {
        abort_if(Gate::denies('eligibility_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eligibilityTest->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
