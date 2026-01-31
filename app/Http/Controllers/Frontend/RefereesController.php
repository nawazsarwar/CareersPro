<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRefereeRequest;
use App\Http\Requests\StoreRefereeRequest;
use App\Http\Requests\UpdateRefereeRequest;
use App\Models\Referee;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RefereesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('referee_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $referees = Referee::with(['user'])->get();

        $users = User::get();

        return view('frontend.referees.index', compact('referees', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('referee_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.referees.create', compact('users'));
    }

    public function store(StoreRefereeRequest $request)
    {
        $referee = Referee::create($request->all());

        return redirect()->route('frontend.referees.index');
    }

    public function edit(Referee $referee)
    {
        abort_if(Gate::denies('referee_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $referee->load('user');

        return view('frontend.referees.edit', compact('referee', 'users'));
    }

    public function update(UpdateRefereeRequest $request, Referee $referee)
    {
        $referee->update($request->all());

        return redirect()->route('frontend.referees.index');
    }

    public function show(Referee $referee)
    {
        abort_if(Gate::denies('referee_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $referee->load('user');

        return view('frontend.referees.show', compact('referee'));
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
