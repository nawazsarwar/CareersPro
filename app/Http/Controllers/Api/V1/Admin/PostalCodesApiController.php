<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostalCodeRequest;
use App\Http\Requests\UpdatePostalCodeRequest;
use App\Http\Resources\Admin\PostalCodeResource;
use App\Models\PostalCode;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PostalCodesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('postal_code_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PostalCodeResource(PostalCode::with(['province'])->get());
    }

    public function store(StorePostalCodeRequest $request)
    {
        $postalCode = PostalCode::create($request->all());

        return (new PostalCodeResource($postalCode))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(PostalCode $postalCode)
    {
        abort_if(Gate::denies('postal_code_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PostalCodeResource($postalCode->load(['province']));
    }

    public function update(UpdatePostalCodeRequest $request, PostalCode $postalCode)
    {
        $postalCode->update($request->all());

        return (new PostalCodeResource($postalCode))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(PostalCode $postalCode)
    {
        abort_if(Gate::denies('postal_code_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $postalCode->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
