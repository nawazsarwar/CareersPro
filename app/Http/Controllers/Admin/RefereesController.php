<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRefereeRequest;
use App\Http\Requests\StoreRefereeRequest;
use App\Http\Requests\UpdateRefereeRequest;
use App\Models\Referee;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RefereesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('referee_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Referee::with(['user'])->select(sprintf('%s.*', (new Referee)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'referee_show';
                $editGate      = 'referee_edit';
                $deleteGate    = 'referee_delete';
                $crudRoutePart = 'referees';

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

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('designation', function ($row) {
                return $row->designation ? $row->designation : '';
            });
            $table->editColumn('mobile', function ($row) {
                return $row->mobile ? $row->mobile : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('address', function ($row) {
                return $row->address ? $row->address : '';
            });
            $table->editColumn('period_known', function ($row) {
                return $row->period_known ? $row->period_known : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user']);

            return $table->make(true);
        }

        $users = User::get();

        return view('admin.referees.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('referee_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.referees.create', compact('users'));
    }

    public function store(StoreRefereeRequest $request)
    {
        $referee = Referee::create($request->all());

        return redirect()->route('admin.referees.index');
    }

    public function edit(Referee $referee)
    {
        abort_if(Gate::denies('referee_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $referee->load('user');

        return view('admin.referees.edit', compact('referee', 'users'));
    }

    public function update(UpdateRefereeRequest $request, Referee $referee)
    {
        $referee->update($request->all());

        return redirect()->route('admin.referees.index');
    }

    public function show(Referee $referee)
    {
        abort_if(Gate::denies('referee_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $referee->load('user');

        return view('admin.referees.show', compact('referee'));
    }

    public function destroy(Referee $referee)
    {
        abort_if(Gate::denies('referee_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $referee->delete();

        return back();
    }

    public function massDestroy(MassDestroyRefereeRequest $request)
    {
        $referees = Referee::find(request('ids'));

        foreach ($referees as $referee) {
            $referee->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
