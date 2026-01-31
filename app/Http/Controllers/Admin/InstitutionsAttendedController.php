<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyInstitutionsAttendedRequest;
use App\Http\Requests\StoreInstitutionsAttendedRequest;
use App\Http\Requests\UpdateInstitutionsAttendedRequest;
use App\Models\Board;
use App\Models\InstitutionsAttended;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class InstitutionsAttendedController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('institutions_attended_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = InstitutionsAttended::with(['user', 'university_board'])->select(sprintf('%s.*', (new InstitutionsAttended)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'institutions_attended_show';
                $editGate      = 'institutions_attended_edit';
                $deleteGate    = 'institutions_attended_delete';
                $crudRoutePart = 'institutions-attendeds';

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

            $table->editColumn('name_of_school', function ($row) {
                return $row->name_of_school ? $row->name_of_school : '';
            });
            $table->editColumn('name_of_college', function ($row) {
                return $row->name_of_college ? $row->name_of_college : '';
            });
            $table->addColumn('university_board_name', function ($row) {
                return $row->university_board ? $row->university_board->name : '';
            });

            $table->editColumn('year_of_joining', function ($row) {
                return $row->year_of_joining ? $row->year_of_joining : '';
            });
            $table->editColumn('year_of_leaving', function ($row) {
                return $row->year_of_leaving ? $row->year_of_leaving : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user', 'university_board']);

            return $table->make(true);
        }

        $users  = User::get();
        $boards = Board::get();

        return view('admin.institutionsAttendeds.index', compact('users', 'boards'));
    }

    public function create()
    {
        abort_if(Gate::denies('institutions_attended_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $university_boards = Board::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.institutionsAttendeds.create', compact('university_boards', 'users'));
    }

    public function store(StoreInstitutionsAttendedRequest $request)
    {
        $institutionsAttended = InstitutionsAttended::create($request->all());

        return redirect()->route('admin.institutions-attendeds.index');
    }

    public function edit(InstitutionsAttended $institutionsAttended)
    {
        abort_if(Gate::denies('institutions_attended_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $university_boards = Board::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $institutionsAttended->load('user', 'university_board');

        return view('admin.institutionsAttendeds.edit', compact('institutionsAttended', 'university_boards', 'users'));
    }

    public function update(UpdateInstitutionsAttendedRequest $request, InstitutionsAttended $institutionsAttended)
    {
        $institutionsAttended->update($request->all());

        return redirect()->route('admin.institutions-attendeds.index');
    }

    public function show(InstitutionsAttended $institutionsAttended)
    {
        abort_if(Gate::denies('institutions_attended_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $institutionsAttended->load('user', 'university_board');

        return view('admin.institutionsAttendeds.show', compact('institutionsAttended'));
    }

    public function destroy(InstitutionsAttended $institutionsAttended)
    {
        abort_if(Gate::denies('institutions_attended_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $institutionsAttended->delete();

        return back();
    }

    public function massDestroy(MassDestroyInstitutionsAttendedRequest $request)
    {
        $institutionsAttendeds = InstitutionsAttended::find(request('ids'));

        foreach ($institutionsAttendeds as $institutionsAttended) {
            $institutionsAttended->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
