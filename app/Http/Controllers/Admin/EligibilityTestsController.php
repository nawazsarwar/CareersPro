<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEligibilityTestRequest;
use App\Http\Requests\StoreEligibilityTestRequest;
use App\Http\Requests\UpdateEligibilityTestRequest;
use App\Models\EligibilityTest;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EligibilityTestsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('eligibility_test_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = EligibilityTest::with(['user'])->select(sprintf('%s.*', (new EligibilityTest)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'eligibility_test_show';
                $editGate      = 'eligibility_test_edit';
                $deleteGate    = 'eligibility_test_delete';
                $crudRoutePart = 'eligibility-tests';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('agency', function ($row) {
                return $row->agency ? $row->agency : '';
            });

            $table->editColumn('subject', function ($row) {
                return $row->subject ? $row->subject : '';
            });
            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user']);

            return $table->make(true);
        }

        return view('admin.eligibilityTests.index');
    }

    public function create()
    {
        abort_if(Gate::denies('eligibility_test_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.eligibilityTests.create', compact('users'));
    }

    public function store(StoreEligibilityTestRequest $request)
    {
        $eligibilityTest = EligibilityTest::create($request->all());

        return redirect()->route('admin.eligibility-tests.index');
    }

    public function edit(EligibilityTest $eligibilityTest)
    {
        abort_if(Gate::denies('eligibility_test_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $eligibilityTest->load('user');

        return view('admin.eligibilityTests.edit', compact('eligibilityTest', 'users'));
    }

    public function update(UpdateEligibilityTestRequest $request, EligibilityTest $eligibilityTest)
    {
        $eligibilityTest->update($request->all());

        return redirect()->route('admin.eligibility-tests.index');
    }

    public function show(EligibilityTest $eligibilityTest)
    {
        abort_if(Gate::denies('eligibility_test_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eligibilityTest->load('user');

        return view('admin.eligibilityTests.show', compact('eligibilityTest'));
    }

    public function destroy(EligibilityTest $eligibilityTest)
    {
        abort_if(Gate::denies('eligibility_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eligibilityTest->delete();

        return back();
    }

    public function massDestroy(MassDestroyEligibilityTestRequest $request)
    {
        $eligibilityTests = EligibilityTest::find(request('ids'));

        foreach ($eligibilityTests as $eligibilityTest) {
            $eligibilityTest->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
