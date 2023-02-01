<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRefereeRequest;
use App\Http\Requests\StoreRefereeRequest;
use App\Http\Requests\UpdateRefereeRequest;
use App\Models\Referee;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RefereesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('referee_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $referees = Referee::all();

        return view('frontend.referees.index', compact('referees'));
    }

    public function create()
    {
        abort_if(Gate::denies('referee_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.referees.create');
    }

    public function store(StoreRefereeRequest $request)
    {
        $referee = Referee::create($request->all());

        return redirect()->route('frontend.referees.index');
    }

    public function edit(Referee $referee)
    {
        abort_if(Gate::denies('referee_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.referees.edit', compact('referee'));
    }

    public function update(UpdateRefereeRequest $request, Referee $referee)
    {
        $referee->update($request->all());

        return redirect()->route('frontend.referees.index');
    }

    public function show(Referee $referee)
    {
        abort_if(Gate::denies('referee_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        Referee::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
