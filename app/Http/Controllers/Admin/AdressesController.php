<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAdressRequest;
use App\Http\Requests\StoreAdressRequest;
use App\Http\Requests\UpdateAdressRequest;
use App\Models\Adress;
use App\Models\Country;
use App\Models\PostalCode;
use App\Models\Province;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AdressesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('adress_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Adress::with(['user', 'postal_code', 'province', 'country'])->select(sprintf('%s.*', (new Adress)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'adress_show';
                $editGate      = 'adress_edit';
                $deleteGate    = 'adress_delete';
                $crudRoutePart = 'adresses';

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

            $table->editColumn('type', function ($row) {
                return $row->type ? Adress::TYPE_SELECT[$row->type] : '';
            });
            $table->editColumn('house_no', function ($row) {
                return $row->house_no ? $row->house_no : '';
            });
            $table->editColumn('street', function ($row) {
                return $row->street ? $row->street : '';
            });
            $table->editColumn('landmark', function ($row) {
                return $row->landmark ? $row->landmark : '';
            });
            $table->editColumn('locality', function ($row) {
                return $row->locality ? $row->locality : '';
            });
            $table->editColumn('city', function ($row) {
                return $row->city ? $row->city : '';
            });
            $table->addColumn('postal_code_name', function ($row) {
                return $row->postal_code ? $row->postal_code->name : '';
            });

            $table->editColumn('district', function ($row) {
                return $row->district ? $row->district : '';
            });
            $table->addColumn('province_name', function ($row) {
                return $row->province ? $row->province->name : '';
            });

            $table->addColumn('country_name', function ($row) {
                return $row->country ? $row->country->name : '';
            });

            $table->editColumn('status', function ($row) {
                return $row->status ? $row->status : '';
            });
            $table->editColumn('remarks', function ($row) {
                return $row->remarks ? $row->remarks : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user', 'postal_code', 'province', 'country']);

            return $table->make(true);
        }

        $users        = User::get();
        $postal_codes = PostalCode::get();
        $provinces    = Province::get();
        $countries    = Country::get();

        return view('admin.adresses.index', compact('users', 'postal_codes', 'provinces', 'countries'));
    }

    public function create()
    {
        abort_if(Gate::denies('adress_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $postal_codes = PostalCode::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $provinces = Province::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.adresses.create', compact('countries', 'postal_codes', 'provinces', 'users'));
    }

    public function store(StoreAdressRequest $request)
    {
        $adress = Adress::create($request->all());

        return redirect()->route('admin.adresses.index');
    }

    public function edit(Adress $adress)
    {
        abort_if(Gate::denies('adress_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $postal_codes = PostalCode::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $provinces = Province::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $adress->load('user', 'postal_code', 'province', 'country');

        return view('admin.adresses.edit', compact('adress', 'countries', 'postal_codes', 'provinces', 'users'));
    }

    public function update(UpdateAdressRequest $request, Adress $adress)
    {
        $adress->update($request->all());

        return redirect()->route('admin.adresses.index');
    }

    public function show(Adress $adress)
    {
        abort_if(Gate::denies('adress_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $adress->load('user', 'postal_code', 'province', 'country');

        return view('admin.adresses.show', compact('adress'));
    }

    public function destroy(Adress $adress)
    {
        abort_if(Gate::denies('adress_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $adress->delete();

        return back();
    }

    public function massDestroy(MassDestroyAdressRequest $request)
    {
        $adresses = Adress::find(request('ids'));

        foreach ($adresses as $adress) {
            $adress->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
