<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCasteRequest;
use App\Http\Requests\StoreCasteRequest;
use App\Http\Requests\UpdateCasteRequest;
use App\Models\Caste;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CastesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('caste_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Caste::query()->select(sprintf('%s.*', (new Caste)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'caste_show';
                $editGate      = 'caste_edit';
                $deleteGate    = 'caste_delete';
                $crudRoutePart = 'castes';

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

        return view('admin.castes.index');
    }

    public function create()
    {
        abort_if(Gate::denies('caste_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.castes.create');
    }

    public function store(StoreCasteRequest $request)
    {
        $caste = Caste::create($request->all());

        return redirect()->route('admin.castes.index');
    }

    public function edit(Caste $caste)
    {
        abort_if(Gate::denies('caste_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.castes.edit', compact('caste'));
    }

    public function update(UpdateCasteRequest $request, Caste $caste)
    {
        $caste->update($request->all());

        return redirect()->route('admin.castes.index');
    }

    public function show(Caste $caste)
    {
        abort_if(Gate::denies('caste_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.castes.show', compact('caste'));
    }

    public function destroy(Caste $caste)
    {
        abort_if(Gate::denies('caste_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $caste->delete();

        return back();
    }

    public function massDestroy(MassDestroyCasteRequest $request)
    {
        $castes = Caste::find(request('ids'));

        foreach ($castes as $caste) {
            $caste->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
