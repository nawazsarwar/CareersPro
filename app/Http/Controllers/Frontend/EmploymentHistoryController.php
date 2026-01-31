<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEmploymentHistoryRequest;
use App\Http\Requests\StoreEmploymentHistoryRequest;
use App\Http\Requests\UpdateEmploymentHistoryRequest;
use App\Models\EmploymentHistory;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmploymentHistoryController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('employment_history_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employmentHistories = EmploymentHistory::with(['user'])->get();

        $users = User::get();

        return view('frontend.employmentHistories.index', compact('employmentHistories', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('employment_history_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.employmentHistories.create', compact('users'));
    }

    public function store(StoreEmploymentHistoryRequest $request)
    {
        $employmentHistory = EmploymentHistory::create($request->all());

        return redirect()->route('frontend.employment-histories.index');
    }

    public function edit(EmploymentHistory $employmentHistory)
    {
        abort_if(Gate::denies('employment_history_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $employmentHistory->load('user');

        return view('frontend.employmentHistories.edit', compact('employmentHistory', 'users'));
    }

    public function update(UpdateEmploymentHistoryRequest $request, EmploymentHistory $employmentHistory)
    {
        $employmentHistory->update($request->all());

        return redirect()->route('frontend.employment-histories.index');
    }

    public function show(EmploymentHistory $employmentHistory)
    {
        abort_if(Gate::denies('employment_history_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employmentHistory->load('user');

        return view('frontend.employmentHistories.show', compact('employmentHistory'));
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
