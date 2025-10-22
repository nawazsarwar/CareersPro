<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEmploymentHistoryRequest;
use App\Http\Requests\StoreEmploymentHistoryRequest;
use App\Http\Requests\UpdateEmploymentHistoryRequest;
use App\Models\EmploymentHistory;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EmploymentHistoryController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('employment_history_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = EmploymentHistory::with(['user'])->select(sprintf('%s.*', (new EmploymentHistory)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'employment_history_show';
                $editGate      = 'employment_history_edit';
                $deleteGate    = 'employment_history_delete';
                $crudRoutePart = 'employment-histories';

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
            $table->editColumn('employer', function ($row) {
                return $row->employer ? $row->employer : '';
            });
            $table->editColumn('type', function ($row) {
                return $row->type ? EmploymentHistory::TYPE_SELECT[$row->type] : '';
            });
            $table->editColumn('designation', function ($row) {
                return $row->designation ? $row->designation : '';
            });

            $table->editColumn('responsibilities', function ($row) {
                return $row->responsibilities ? $row->responsibilities : '';
            });
            $table->editColumn('reason_for_leaving', function ($row) {
                return $row->reason_for_leaving ? $row->reason_for_leaving : '';
            });
            $table->editColumn('pay_band', function ($row) {
                return $row->pay_band ? $row->pay_band : '';
            });
            $table->editColumn('basic_pay', function ($row) {
                return $row->basic_pay ? $row->basic_pay : '';
            });
            $table->editColumn('gross_pay', function ($row) {
                return $row->gross_pay ? $row->gross_pay : '';
            });
            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user']);

            return $table->make(true);
        }

        return view('admin.employmentHistories.index');
    }

    public function create()
    {
        abort_if(Gate::denies('employment_history_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.employmentHistories.create', compact('users'));
    }

    public function store(StoreEmploymentHistoryRequest $request)
    {
        $employmentHistory = EmploymentHistory::create($request->all());

        return redirect()->route('admin.employment-histories.index');
    }

    public function edit(EmploymentHistory $employmentHistory)
    {
        abort_if(Gate::denies('employment_history_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $employmentHistory->load('user');

        return view('admin.employmentHistories.edit', compact('employmentHistory', 'users'));
    }

    public function update(UpdateEmploymentHistoryRequest $request, EmploymentHistory $employmentHistory)
    {
        $employmentHistory->update($request->all());

        return redirect()->route('admin.employment-histories.index');
    }

    public function show(EmploymentHistory $employmentHistory)
    {
        abort_if(Gate::denies('employment_history_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employmentHistory->load('user');

        return view('admin.employmentHistories.show', compact('employmentHistory'));
    }

    public function destroy(EmploymentHistory $employmentHistory)
    {
        abort_if(Gate::denies('employment_history_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employmentHistory->delete();

        return back();
    }

    public function massDestroy(MassDestroyEmploymentHistoryRequest $request)
    {
        $employmentHistories = EmploymentHistory::find(request('ids'));

        foreach ($employmentHistories as $employmentHistory) {
            $employmentHistory->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
