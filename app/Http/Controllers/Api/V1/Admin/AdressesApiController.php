<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdressRequest;
use App\Http\Requests\UpdateAdressRequest;
use App\Http\Resources\Admin\AdressResource;
use App\Models\Adress;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdressesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('adress_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AdressResource(Adress::with(['postal_code', 'province', 'country', 'user'])->get());
    }

    public function store(StoreAdressRequest $request)
    {
        $adress = Adress::create($request->all());

        return (new AdressResource($adress))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Adress $adress)
    {
        abort_if(Gate::denies('adress_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AdressResource($adress->load(['postal_code', 'province', 'country', 'user']));
    }

    public function update(UpdateAdressRequest $request, Adress $adress)
    {
        $adress->update($request->all());

        return (new AdressResource($adress))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Adress $adress)
    {
        abort_if(Gate::denies('adress_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $adress->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
