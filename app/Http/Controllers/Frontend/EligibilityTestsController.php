<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEligibilityTestRequest;
use App\Http\Requests\StoreEligibilityTestRequest;
use App\Http\Requests\UpdateEligibilityTestRequest;
use App\Models\EligibilityTest;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EligibilityTestsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('eligibility_test_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eligibilityTests = EligibilityTest::with(['user'])->get();

        return view('frontend.eligibilityTests.index', compact('eligibilityTests'));
    }

    public function create()
    {
        abort_if(Gate::denies('eligibility_test_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.eligibilityTests.create', compact('users'));
    }

    public function store(StoreEligibilityTestRequest $request)
    {
        $eligibilityTest = EligibilityTest::create($request->all());

        return redirect()->route('frontend.eligibility-tests.index');
    }

    public function edit(EligibilityTest $eligibilityTest)
    {
        abort_if(Gate::denies('eligibility_test_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $eligibilityTest->load('user');

        return view('frontend.eligibilityTests.edit', compact('eligibilityTest', 'users'));
    }

    public function update(UpdateEligibilityTestRequest $request, EligibilityTest $eligibilityTest)
    {
        $eligibilityTest->update($request->all());

        return redirect()->route('frontend.eligibility-tests.index');
    }

    public function show(EligibilityTest $eligibilityTest)
    {
        abort_if(Gate::denies('eligibility_test_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eligibilityTest->load('user');

        return view('frontend.eligibilityTests.show', compact('eligibilityTest'));
    }

    public function destroy(EligibilityTest $eligibilityTest)
    {
        abort_if(Gate::denies('eligibility_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eligibilityTest->delete();

        return back();
    }

    public function massDestroy(MassDestroyEligibilityTestRequest $request)
    {
        EligibilityTest::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
