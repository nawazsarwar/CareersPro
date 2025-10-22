<?php

namespace App\Http\Controllers\Frontend;

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

class ForeignVisitsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('foreign_visit_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $foreignVisits = ForeignVisit::with(['country', 'user'])->get();

        return view('frontend.foreignVisits.index', compact('foreignVisits'));
    }

    public function create()
    {
        abort_if(Gate::denies('foreign_visit_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.foreignVisits.create', compact('countries', 'users'));
    }

    public function store(StoreForeignVisitRequest $request)
    {
        $foreignVisit = ForeignVisit::create($request->all());

        return redirect()->route('frontend.foreign-visits.index');
    }

    public function edit(ForeignVisit $foreignVisit)
    {
        abort_if(Gate::denies('foreign_visit_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $foreignVisit->load('country', 'user');

        return view('frontend.foreignVisits.edit', compact('countries', 'foreignVisit', 'users'));
    }

    public function update(UpdateForeignVisitRequest $request, ForeignVisit $foreignVisit)
    {
        $foreignVisit->update($request->all());

        return redirect()->route('frontend.foreign-visits.index');
    }

    public function show(ForeignVisit $foreignVisit)
    {
        abort_if(Gate::denies('foreign_visit_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $foreignVisit->load('country', 'user');

        return view('frontend.foreignVisits.show', compact('foreignVisit'));
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
