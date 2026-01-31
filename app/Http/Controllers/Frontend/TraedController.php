<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTraedRequest;
use App\Http\Requests\StoreTraedRequest;
use App\Http\Requests\UpdateTraedRequest;
use App\Models\Traed;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TraedController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('traed_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $traeds = Traed::with(['user'])->get();

        $users = User::get();

        return view('frontend.traeds.index', compact('traeds', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('traed_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.traeds.create', compact('users'));
    }

    public function store(StoreTraedRequest $request)
    {
        $traed = Traed::create($request->all());

        return redirect()->route('frontend.traeds.index');
    }

    public function edit(Traed $traed)
    {
        abort_if(Gate::denies('traed_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $traed->load('user');

        return view('frontend.traeds.edit', compact('traed', 'users'));
    }

    public function update(UpdateTraedRequest $request, Traed $traed)
    {
        $traed->update($request->all());

        return redirect()->route('frontend.traeds.index');
    }

    public function show(Traed $traed)
    {
        abort_if(Gate::denies('traed_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $traed->load('user');

        return view('frontend.traeds.show', compact('traed'));
    }

    public function destroy(Traed $traed)
    {
        abort_if(Gate::denies('traed_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $traed->delete();

        return back();
    }

    public function massDestroy(MassDestroyTraedRequest $request)
    {
        $traeds = Traed::find(request('ids'));

        foreach ($traeds as $traed) {
            $traed->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
