<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPhotoRequest;
use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;
use App\Models\Photo;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class PhotosController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('photo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $photos = Photo::with(['user', 'media'])->get();

        $users = User::get();

        return view('frontend.photos.index', compact('photos', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('photo_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.photos.create', compact('users'));
    }

    public function store(StorePhotoRequest $request)
    {
        $photo = Photo::create($request->all());

        if ($request->input('photograph', false)) {
            $photo->addMedia(storage_path('tmp/uploads/' . basename($request->input('photograph'))))->toMediaCollection('photograph');
        }

        if ($request->input('signature', false)) {
            $photo->addMedia(storage_path('tmp/uploads/' . basename($request->input('signature'))))->toMediaCollection('signature');
        }

        if ($request->input('thumb_impression', false)) {
            $photo->addMedia(storage_path('tmp/uploads/' . basename($request->input('thumb_impression'))))->toMediaCollection('thumb_impression');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $photo->id]);
        }

        return redirect()->route('frontend.photos.index');
    }

    public function edit(Photo $photo)
    {
        abort_if(Gate::denies('photo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $photo->load('user');

        return view('frontend.photos.edit', compact('photo', 'users'));
    }

    public function update(UpdatePhotoRequest $request, Photo $photo)
    {
        $photo->update($request->all());

        if ($request->input('photograph', false)) {
            if (! $photo->photograph || $request->input('photograph') !== $photo->photograph->file_name) {
                if ($photo->photograph) {
                    $photo->photograph->delete();
                }
                $photo->addMedia(storage_path('tmp/uploads/' . basename($request->input('photograph'))))->toMediaCollection('photograph');
            }
        } elseif ($photo->photograph) {
            $photo->photograph->delete();
        }

        if ($request->input('signature', false)) {
            if (! $photo->signature || $request->input('signature') !== $photo->signature->file_name) {
                if ($photo->signature) {
                    $photo->signature->delete();
                }
                $photo->addMedia(storage_path('tmp/uploads/' . basename($request->input('signature'))))->toMediaCollection('signature');
            }
        } elseif ($photo->signature) {
            $photo->signature->delete();
        }

        if ($request->input('thumb_impression', false)) {
            if (! $photo->thumb_impression || $request->input('thumb_impression') !== $photo->thumb_impression->file_name) {
                if ($photo->thumb_impression) {
                    $photo->thumb_impression->delete();
                }
                $photo->addMedia(storage_path('tmp/uploads/' . basename($request->input('thumb_impression'))))->toMediaCollection('thumb_impression');
            }
        } elseif ($photo->thumb_impression) {
            $photo->thumb_impression->delete();
        }

        return redirect()->route('frontend.photos.index');
    }

    public function show(Photo $photo)
    {
        abort_if(Gate::denies('photo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $photo->load('user');

        return view('frontend.photos.show', compact('photo'));
    }

    public function destroy(Photo $photo)
    {
        abort_if(Gate::denies('photo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $photo->delete();

        return back();
    }

    public function massDestroy(MassDestroyPhotoRequest $request)
    {
        $photos = Photo::find(request('ids'));

        foreach ($photos as $photo) {
            $photo->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('photo_create') && Gate::denies('photo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Photo();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
