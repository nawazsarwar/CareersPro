<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWorkExperienceRequest;
use App\Http\Requests\StoreWorkExperienceRequest;
use App\Http\Requests\UpdateWorkExperienceRequest;
use App\Models\User;
use App\Models\WorkExperience;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class WorkExperienceController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('work_experience_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = WorkExperience::with(['user'])->select(sprintf('%s.*', (new WorkExperience())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'work_experience_show';
                $editGate = 'work_experience_edit';
                $deleteGate = 'work_experience_delete';
                $crudRoutePart = 'work-experiences';

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
                return $row->type ? WorkExperience::TYPE_SELECT[$row->type] : '';
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

        return view('admin.workExperiences.index');
    }

    public function create()
    {
        abort_if(Gate::denies('work_experience_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.workExperiences.create', compact('users'));
    }

    public function store(StoreWorkExperienceRequest $request)
    {
        $workExperience = WorkExperience::create($request->all());

        return redirect()->route('admin.work-experiences.index');
    }

    public function edit(WorkExperience $workExperience)
    {
        abort_if(Gate::denies('work_experience_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $workExperience->load('user');

        return view('admin.workExperiences.edit', compact('users', 'workExperience'));
    }

    public function update(UpdateWorkExperienceRequest $request, WorkExperience $workExperience)
    {
        $workExperience->update($request->all());

        return redirect()->route('admin.work-experiences.index');
    }

    public function show(WorkExperience $workExperience)
    {
        abort_if(Gate::denies('work_experience_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workExperience->load('user');

        return view('admin.workExperiences.show', compact('workExperience'));
    }

    public function destroy(WorkExperience $workExperience)
    {
        abort_if(Gate::denies('work_experience_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workExperience->delete();

        return back();
    }

    public function massDestroy(MassDestroyWorkExperienceRequest $request)
    {
        WorkExperience::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
