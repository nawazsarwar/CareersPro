<?php

namespace App\Http\Controllers\Frontend;

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

class AdressesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('adress_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $adresses = Adress::with(['postal_code', 'province', 'country', 'user'])->get();

        return view('frontend.adresses.index', compact('adresses'));
    }

    public function create()
    {
        abort_if(Gate::denies('adress_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $postal_codes = PostalCode::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $provinces = Province::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.adresses.create', compact('countries', 'postal_codes', 'provinces', 'users'));
    }

    public function store(StoreAdressRequest $request)
    {
        $adress = Adress::create($request->all());

        return redirect()->route('frontend.adresses.index');
    }

    public function edit(Adress $adress)
    {
        abort_if(Gate::denies('adress_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $postal_codes = PostalCode::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $provinces = Province::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $adress->load('postal_code', 'province', 'country', 'user');

        return view('frontend.adresses.edit', compact('adress', 'countries', 'postal_codes', 'provinces', 'users'));
    }

    public function update(UpdateAdressRequest $request, Adress $adress)
    {
        $adress->update($request->all());

        return redirect()->route('frontend.adresses.index');
    }

    public function show(Adress $adress)
    {
        abort_if(Gate::denies('adress_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $adress->load('postal_code', 'province', 'country', 'user');

        return view('frontend.adresses.show', compact('adress'));
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
