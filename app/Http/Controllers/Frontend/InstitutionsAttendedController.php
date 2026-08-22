<?php

namespace App\Http\Controllers\Frontend;

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

class InstitutionsAttendedController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('institutions_attended_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $institutionsAttendeds = InstitutionsAttended::with(['user', 'university_board'])->get();

        $users = User::get();

        $boards = Board::get();

        return view('frontend.institutionsAttendeds.index', compact('boards', 'institutionsAttendeds', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('institutions_attended_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $university_boards = Board::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.institutionsAttendeds.create', compact('university_boards', 'users'));
    }

    public function store(StoreInstitutionsAttendedRequest $request)
    {
        $institutionsAttended = InstitutionsAttended::create($request->all());

        return redirect()->route('frontend.institutions-attendeds.index');
    }

    public function edit(InstitutionsAttended $institutionsAttended)
    {
        abort_if(Gate::denies('institutions_attended_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $university_boards = Board::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $institutionsAttended->load('user', 'university_board');

        return view('frontend.institutionsAttendeds.edit', compact('institutionsAttended', 'university_boards', 'users'));
    }

    public function update(UpdateInstitutionsAttendedRequest $request, InstitutionsAttended $institutionsAttended)
    {
        $institutionsAttended->update($request->all());

        return redirect()->route('frontend.institutions-attendeds.index');
    }

    public function show(InstitutionsAttended $institutionsAttended)
    {
        abort_if(Gate::denies('institutions_attended_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $institutionsAttended->load('user', 'university_board');

        return view('frontend.institutionsAttendeds.show', compact('institutionsAttended'));
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
