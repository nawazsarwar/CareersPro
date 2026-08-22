<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPostRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Advertisement;
use App\Models\Post;
use App\Models\PostType;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class PostsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('post_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $posts = Post::with(['advertisement', 'posttype', 'added_by'])->get();

        $advertisements = Advertisement::get();

        $post_types = PostType::get();

        $users = User::get();

        return view('frontend.posts.index', compact('advertisements', 'post_types', 'posts', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('post_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisements = Advertisement::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $posttypes = PostType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $added_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.posts.create', compact('added_bies', 'advertisements', 'posttypes'));
    }

    public function store(StorePostRequest $request)
    {
        $post = Post::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $post->id]);
        }

        return redirect()->route('frontend.posts.index');
    }

    public function edit(Post $post)
    {
        abort_if(Gate::denies('post_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisements = Advertisement::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $posttypes = PostType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $added_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $post->load('advertisement', 'posttype', 'added_by');

        return view('frontend.posts.edit', compact('added_bies', 'advertisements', 'post', 'posttypes'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->all());

        return redirect()->route('frontend.posts.index');
    }

    public function show(Post $post)
    {
        abort_if(Gate::denies('post_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $post->load('advertisement', 'posttype', 'added_by');

        return view('frontend.posts.show', compact('post'));
    }

    public function destroy(Post $post)
    {
        abort_if(Gate::denies('post_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $post->delete();

        return back();
    }

    public function massDestroy(MassDestroyPostRequest $request)
    {
        $posts = Post::find(request('ids'));

        foreach ($posts as $post) {
            $post->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('post_create') && Gate::denies('post_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Post();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
