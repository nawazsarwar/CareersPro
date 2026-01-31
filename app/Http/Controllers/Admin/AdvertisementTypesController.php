<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAdvertisementTypeRequest;
use App\Http\Requests\StoreAdvertisementTypeRequest;
use App\Http\Requests\UpdateAdvertisementTypeRequest;
use App\Models\AdvertisementType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AdvertisementTypesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('advertisement_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AdvertisementType::query()->select(sprintf('%s.*', (new AdvertisementType)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'advertisement_type_show';
                $editGate      = 'advertisement_type_edit';
                $deleteGate    = 'advertisement_type_delete';
                $crudRoutePart = 'advertisement-types';

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
            $table->editColumn('title', function ($row) {
                return $row->title ? AdvertisementType::TITLE_SELECT[$row->title] : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.advertisementTypes.index');
    }

    public function create()
    {
        abort_if(Gate::denies('advertisement_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.advertisementTypes.create');
    }

    public function store(StoreAdvertisementTypeRequest $request)
    {
        $advertisementType = AdvertisementType::create($request->all());

        return redirect()->route('admin.advertisement-types.index');
    }

    public function edit(AdvertisementType $advertisementType)
    {
        abort_if(Gate::denies('advertisement_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.advertisementTypes.edit', compact('advertisementType'));
    }

    public function update(UpdateAdvertisementTypeRequest $request, AdvertisementType $advertisementType)
    {
        $advertisementType->update($request->all());

        return redirect()->route('admin.advertisement-types.index');
    }

    public function show(AdvertisementType $advertisementType)
    {
        abort_if(Gate::denies('advertisement_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.advertisementTypes.show', compact('advertisementType'));
    }

    public function destroy(AdvertisementType $advertisementType)
    {
        abort_if(Gate::denies('advertisement_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisementType->delete();

        return back();
    }

    public function massDestroy(MassDestroyAdvertisementTypeRequest $request)
    {
        $advertisementTypes = AdvertisementType::find(request('ids'));

        foreach ($advertisementTypes as $advertisementType) {
            $advertisementType->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
