<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyForeignVisitRequest;
use App\Http\Requests\StoreForeignVisitRequest;
use App\Http\Requests\UpdateForeignVisitRequest;
use App\Models\Country;
use App\Models\ForeignVisit;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ForeignVisitsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('foreign_visit_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ForeignVisit::with(['user', 'country'])->select(sprintf('%s.*', (new ForeignVisit)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'foreign_visit_show';
                $editGate      = 'foreign_visit_edit';
                $deleteGate    = 'foreign_visit_delete';
                $crudRoutePart = 'foreign-visits';

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

            $table->addColumn('country_name', function ($row) {
                return $row->country ? $row->country->name : '';
            });

            $table->editColumn('duration', function ($row) {
                return $row->duration ? $row->duration : '';
            });
            $table->editColumn('purpose', function ($row) {
                return $row->purpose ? $row->purpose : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user', 'country']);

            return $table->make(true);
        }

        $users     = User::get();
        $countries = Country::get();

        return view('admin.foreignVisits.index', compact('users', 'countries'));
    }

    public function create()
    {
        abort_if(Gate::denies('foreign_visit_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.foreignVisits.create', compact('countries', 'users'));
    }

    public function store(StoreForeignVisitRequest $request)
    {
        $foreignVisit = ForeignVisit::create($request->all());

        return redirect()->route('admin.foreign-visits.index');
    }

    public function edit(ForeignVisit $foreignVisit)
    {
        abort_if(Gate::denies('foreign_visit_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $foreignVisit->load('user', 'country');

        return view('admin.foreignVisits.edit', compact('countries', 'foreignVisit', 'users'));
    }

    public function update(UpdateForeignVisitRequest $request, ForeignVisit $foreignVisit)
    {
        $foreignVisit->update($request->all());

        return redirect()->route('admin.foreign-visits.index');
    }

    public function show(ForeignVisit $foreignVisit)
    {
        abort_if(Gate::denies('foreign_visit_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $foreignVisit->load('user', 'country');

        return view('admin.foreignVisits.show', compact('foreignVisit'));
    }

    public function destroy(ForeignVisit $foreignVisit)
    {
        abort_if(Gate::denies('foreign_visit_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $foreignVisit->delete();

        return back();
    }

    public function massDestroy(MassDestroyForeignVisitRequest $request)
    {
        $foreignVisits = ForeignVisit::find(request('ids'));

        foreach ($foreignVisits as $foreignVisit) {
            $foreignVisit->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
