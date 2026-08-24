<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPostRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Advertisement;
use App\Models\Post;
use App\Models\PostType;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class PostsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('post_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Post::with(['advertisement', 'posttype', 'added_by']);

        // Handle Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('serial_no', 'like', "%{$search}%");
        }

        // Handle Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Sorting
        $sortField = $request->input('sort', 'id');
        $sortDir = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDir);

        $posts = $query->paginate(15)->withQueryString();

        $advertisements = Advertisement::get();
        $post_types     = PostType::get();
        $users          = User::get();

        return view('admin.posts.index', compact('posts', 'advertisements', 'post_types', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('post_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisements = Advertisement::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');
        $posttypes = PostType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $added_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.posts.create', compact('added_bies', 'advertisements', 'posttypes'));
    }

    public function store(StorePostRequest $request)
    {
        $post = Post::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $post->id]);
        }

        return redirect()->route('admin.posts.index');
    }

    public function edit(Post $post)
    {
        abort_if(Gate::denies('post_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisements = Advertisement::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');
        $posttypes = PostType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $added_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $post->load('advertisement', 'posttype', 'added_by');

        return view('admin.posts.edit', compact('added_bies', 'advertisements', 'post', 'posttypes'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->all());

        return redirect()->route('admin.posts.index');
    }

    public function show(Post $post)
    {
        abort_if(Gate::denies('post_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $post->load('advertisement', 'posttype', 'added_by', 'postApplicationForms');

        return view('admin.posts.show', compact('post'));
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
