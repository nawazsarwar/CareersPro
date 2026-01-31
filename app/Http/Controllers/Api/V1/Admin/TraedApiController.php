<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTraedRequest;
use App\Http\Requests\UpdateTraedRequest;
use App\Http\Resources\Admin\TraedResource;
use App\Models\Traed;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TraedApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('traed_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TraedResource(Traed::with(['user'])->get());
    }

    public function store(StoreTraedRequest $request)
    {
        $traed = Traed::create($request->all());

        return (new TraedResource($traed))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Traed $traed)
    {
        abort_if(Gate::denies('traed_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TraedResource($traed->load(['user']));
    }

    public function update(UpdateTraedRequest $request, Traed $traed)
    {
        $traed->update($request->all());

        return (new TraedResource($traed))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Traed $traed)
    {
        abort_if(Gate::denies('traed_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $traed->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
