<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;
use App\Http\Resources\Admin\PhotoResource;
use App\Models\Photo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PhotosApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('photo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PhotoResource(Photo::with(['user'])->get());
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

        return (new PhotoResource($photo))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Photo $photo)
    {
        abort_if(Gate::denies('photo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PhotoResource($photo->load(['user']));
    }

    public function update(UpdatePhotoRequest $request, Photo $photo)
    {
        $photo->update($request->all());

        if ($request->input('photograph', false)) {
            if (!$photo->photograph || $request->input('photograph') !== $photo->photograph->file_name) {
                if ($photo->photograph) {
                    $photo->photograph->delete();
                }
                $photo->addMedia(storage_path('tmp/uploads/' . basename($request->input('photograph'))))->toMediaCollection('photograph');
            }
        } elseif ($photo->photograph) {
            $photo->photograph->delete();
        }

        if ($request->input('signature', false)) {
            if (!$photo->signature || $request->input('signature') !== $photo->signature->file_name) {
                if ($photo->signature) {
                    $photo->signature->delete();
                }
                $photo->addMedia(storage_path('tmp/uploads/' . basename($request->input('signature'))))->toMediaCollection('signature');
            }
        } elseif ($photo->signature) {
            $photo->signature->delete();
        }

        if ($request->input('thumb_impression', false)) {
            if (!$photo->thumb_impression || $request->input('thumb_impression') !== $photo->thumb_impression->file_name) {
                if ($photo->thumb_impression) {
                    $photo->thumb_impression->delete();
                }
                $photo->addMedia(storage_path('tmp/uploads/' . basename($request->input('thumb_impression'))))->toMediaCollection('thumb_impression');
            }
        } elseif ($photo->thumb_impression) {
            $photo->thumb_impression->delete();
        }

        return (new PhotoResource($photo))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Photo $photo)
    {
        abort_if(Gate::denies('photo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $photo->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
