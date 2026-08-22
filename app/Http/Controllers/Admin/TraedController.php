<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTraedRequest;
use App\Http\Requests\StoreTraedRequest;
use App\Http\Requests\UpdateTraedRequest;
use App\Models\Traed;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TraedController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('traed_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Traed::with(['user'])->select(sprintf('%s.*', (new Traed)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'traed_show';
                $editGate      = 'traed_edit';
                $deleteGate    = 'traed_delete';
                $crudRoutePart = 'traeds';

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

            $table->editColumn('teaching_at_bachelors_level_in_years', function ($row) {
                return $row->teaching_at_bachelors_level_in_years ? $row->teaching_at_bachelors_level_in_years : '';
            });
            $table->editColumn('teaching_at_masters_level_in_years', function ($row) {
                return $row->teaching_at_masters_level_in_years ? $row->teaching_at_masters_level_in_years : '';
            });
            $table->editColumn('research_at_masters_level_in_years', function ($row) {
                return $row->research_at_masters_level_in_years ? $row->research_at_masters_level_in_years : '';
            });
            $table->editColumn('research_at_post_doctorals_level_in_years', function ($row) {
                return $row->research_at_post_doctorals_level_in_years ? $row->research_at_post_doctorals_level_in_years : '';
            });
            $table->editColumn('experience_in_educational_administration_in_years', function ($row) {
                return $row->experience_in_educational_administration_in_years ? $row->experience_in_educational_administration_in_years : '';
            });
            $table->editColumn('any_other_administrative_experience_in_years', function ($row) {
                return $row->any_other_administrative_experience_in_years ? $row->any_other_administrative_experience_in_years : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user']);

            return $table->make(true);
        }

        $users = User::get();

        return view('admin.traeds.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('traed_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.traeds.create', compact('users'));
    }

    public function store(StoreTraedRequest $request)
    {
        $traed = Traed::create($request->all());

        return redirect()->route('admin.traeds.index');
    }

    public function edit(Traed $traed)
    {
        abort_if(Gate::denies('traed_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $traed->load('user');

        return view('admin.traeds.edit', compact('traed', 'users'));
    }

    public function update(UpdateTraedRequest $request, Traed $traed)
    {
        $traed->update($request->all());

        return redirect()->route('admin.traeds.index');
    }

    public function show(Traed $traed)
    {
        abort_if(Gate::denies('traed_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $traed->load('user');

        return view('admin.traeds.show', compact('traed'));
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
