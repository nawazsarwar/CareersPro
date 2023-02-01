<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPostTypeRequest;
use App\Http\Requests\StorePostTypeRequest;
use App\Http\Requests\UpdatePostTypeRequest;
use App\Models\PostType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PostTypesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('post_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $postTypes = PostType::all();

        return view('admin.postTypes.index', compact('postTypes'));
    }

    public function create()
    {
        abort_if(Gate::denies('post_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.postTypes.create');
    }

    public function store(StorePostTypeRequest $request)
    {
        $postType = PostType::create($request->all());

        return redirect()->route('admin.post-types.index');
    }

    public function edit(PostType $postType)
    {
        abort_if(Gate::denies('post_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.postTypes.edit', compact('postType'));
    }

    public function update(UpdatePostTypeRequest $request, PostType $postType)
    {
        $postType->update($request->all());

        return redirect()->route('admin.post-types.index');
    }

    public function show(PostType $postType)
    {
        abort_if(Gate::denies('post_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.postTypes.show', compact('postType'));
    }

    public function destroy(PostType $postType)
    {
        abort_if(Gate::denies('post_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $postType->delete();

        return back();
    }

    public function massDestroy(MassDestroyPostTypeRequest $request)
    {
        PostType::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
