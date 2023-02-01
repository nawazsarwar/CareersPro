<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPostalCodeRequest;
use App\Http\Requests\StorePostalCodeRequest;
use App\Http\Requests\UpdatePostalCodeRequest;
use App\Models\PostalCode;
use App\Models\Province;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PostalCodesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('postal_code_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = PostalCode::with(['province'])->select(sprintf('%s.*', (new PostalCode())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'postal_code_show';
                $editGate = 'postal_code_edit';
                $deleteGate = 'postal_code_delete';
                $crudRoutePart = 'postal-codes';

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
            $table->editColumn('locality', function ($row) {
                return $row->locality ? $row->locality : '';
            });
            $table->editColumn('code', function ($row) {
                return $row->code ? $row->code : '';
            });
            $table->editColumn('sub_district', function ($row) {
                return $row->sub_district ? $row->sub_district : '';
            });
            $table->editColumn('district', function ($row) {
                return $row->district ? $row->district : '';
            });
            $table->addColumn('province_name', function ($row) {
                return $row->province ? $row->province->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'province']);

            return $table->make(true);
        }

        return view('admin.postalCodes.index');
    }

    public function create()
    {
        abort_if(Gate::denies('postal_code_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $provinces = Province::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.postalCodes.create', compact('provinces'));
    }

    public function store(StorePostalCodeRequest $request)
    {
        $postalCode = PostalCode::create($request->all());

        return redirect()->route('admin.postal-codes.index');
    }

    public function edit(PostalCode $postalCode)
    {
        abort_if(Gate::denies('postal_code_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $provinces = Province::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $postalCode->load('province');

        return view('admin.postalCodes.edit', compact('postalCode', 'provinces'));
    }

    public function update(UpdatePostalCodeRequest $request, PostalCode $postalCode)
    {
        $postalCode->update($request->all());

        return redirect()->route('admin.postal-codes.index');
    }

    public function show(PostalCode $postalCode)
    {
        abort_if(Gate::denies('postal_code_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $postalCode->load('province');

        return view('admin.postalCodes.show', compact('postalCode'));
    }

    public function destroy(PostalCode $postalCode)
    {
        abort_if(Gate::denies('postal_code_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $postalCode->delete();

        return back();
    }

    public function massDestroy(MassDestroyPostalCodeRequest $request)
    {
        PostalCode::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
