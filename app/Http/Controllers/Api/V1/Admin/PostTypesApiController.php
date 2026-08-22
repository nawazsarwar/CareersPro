<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostTypeRequest;
use App\Http\Requests\UpdatePostTypeRequest;
use App\Http\Resources\Admin\PostTypeResource;
use App\Models\PostType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PostTypesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('post_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PostTypeResource(PostType::all());
    }

    public function store(StorePostTypeRequest $request)
    {
        $postType = PostType::create($request->all());

        return (new PostTypeResource($postType))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(PostType $postType)
    {
        abort_if(Gate::denies('post_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PostTypeResource($postType);
    }

    public function update(UpdatePostTypeRequest $request, PostType $postType)
    {
        $postType->update($request->all());

        return (new PostTypeResource($postType))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(PostType $postType)
    {
        abort_if(Gate::denies('post_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $postType->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
