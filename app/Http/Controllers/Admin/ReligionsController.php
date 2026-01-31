<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyReligionRequest;
use App\Http\Requests\StoreReligionRequest;
use App\Http\Requests\UpdateReligionRequest;
use App\Models\Religion;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ReligionsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('religion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Religion::query()->select(sprintf('%s.*', (new Religion)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'religion_show';
                $editGate      = 'religion_edit';
                $deleteGate    = 'religion_delete';
                $crudRoutePart = 'religions';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.religions.index');
    }

    public function create()
    {
        abort_if(Gate::denies('religion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.religions.create');
    }

    public function store(StoreReligionRequest $request)
    {
        $religion = Religion::create($request->all());

        return redirect()->route('admin.religions.index');
    }

    public function edit(Religion $religion)
    {
        abort_if(Gate::denies('religion_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.religions.edit', compact('religion'));
    }

    public function update(UpdateReligionRequest $request, Religion $religion)
    {
        $religion->update($request->all());

        return redirect()->route('admin.religions.index');
    }

    public function show(Religion $religion)
    {
        abort_if(Gate::denies('religion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.religions.show', compact('religion'));
    }

    public function destroy(Religion $religion)
    {
        abort_if(Gate::denies('religion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $religion->delete();

        return back();
    }

    public function massDestroy(MassDestroyReligionRequest $request)
    {
        $religions = Religion::find(request('ids'));

        foreach ($religions as $religion) {
            $religion->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
