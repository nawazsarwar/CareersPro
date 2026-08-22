<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProvinceRequest;
use App\Http\Requests\StoreProvinceRequest;
use App\Http\Requests\UpdateProvinceRequest;
use App\Models\Province;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProvincesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('province_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Province::query()->select(sprintf('%s.*', (new Province)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'province_show';
                $editGate      = 'province_edit';
                $deleteGate    = 'province_delete';
                $crudRoutePart = 'provinces';

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
            $table->editColumn('type', function ($row) {
                return $row->type ? $row->type : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('iso_3166_2_in', function ($row) {
                return $row->iso_3166_2_in ? $row->iso_3166_2_in : '';
            });
            $table->editColumn('vehicle_code', function ($row) {
                return $row->vehicle_code ? $row->vehicle_code : '';
            });
            $table->editColumn('zone', function ($row) {
                return $row->zone ? $row->zone : '';
            });
            $table->editColumn('capital', function ($row) {
                return $row->capital ? $row->capital : '';
            });
            $table->editColumn('largest_city', function ($row) {
                return $row->largest_city ? $row->largest_city : '';
            });
            $table->editColumn('statehood', function ($row) {
                return $row->statehood ? $row->statehood : '';
            });
            $table->editColumn('population', function ($row) {
                return $row->population ? $row->population : '';
            });
            $table->editColumn('area', function ($row) {
                return $row->area ? $row->area : '';
            });
            $table->editColumn('official_languages', function ($row) {
                return $row->official_languages ? $row->official_languages : '';
            });
            $table->editColumn('additional_official_languages', function ($row) {
                return $row->additional_official_languages ? $row->additional_official_languages : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.provinces.index');
    }

    public function create()
    {
        abort_if(Gate::denies('province_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.provinces.create');
    }

    public function store(StoreProvinceRequest $request)
    {
        $province = Province::create($request->all());

        return redirect()->route('admin.provinces.index');
    }

    public function edit(Province $province)
    {
        abort_if(Gate::denies('province_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.provinces.edit', compact('province'));
    }

    public function update(UpdateProvinceRequest $request, Province $province)
    {
        $province->update($request->all());

        return redirect()->route('admin.provinces.index');
    }

    public function show(Province $province)
    {
        abort_if(Gate::denies('province_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.provinces.show', compact('province'));
    }

    public function destroy(Province $province)
    {
        abort_if(Gate::denies('province_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $province->delete();

        return back();
    }

    public function massDestroy(MassDestroyProvinceRequest $request)
    {
        $provinces = Province::find(request('ids'));

        foreach ($provinces as $province) {
            $province->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
