<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreAdvertisementRequest;
use App\Http\Requests\UpdateAdvertisementRequest;
use App\Http\Resources\Admin\AdvertisementResource;
use App\Models\Advertisement;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdvertisementsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('advertisement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AdvertisementResource(Advertisement::with(['type', 'added_by', 'approved_by'])->get());
    }

    public function store(StoreAdvertisementRequest $request)
    {
        $advertisement = Advertisement::create($request->all());

        if ($request->input('document', false)) {
            $advertisement->addMedia(storage_path('tmp/uploads/' . basename($request->input('document'))))->toMediaCollection('document');
        }

        return (new AdvertisementResource($advertisement))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Advertisement $advertisement)
    {
        abort_if(Gate::denies('advertisement_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AdvertisementResource($advertisement->load(['type', 'added_by', 'approved_by']));
    }

    public function update(UpdateAdvertisementRequest $request, Advertisement $advertisement)
    {
        $advertisement->update($request->all());

        if ($request->input('document', false)) {
            if (! $advertisement->document || $request->input('document') !== $advertisement->document->file_name) {
                if ($advertisement->document) {
                    $advertisement->document->delete();
                }
                $advertisement->addMedia(storage_path('tmp/uploads/' . basename($request->input('document'))))->toMediaCollection('document');
            }
        } elseif ($advertisement->document) {
            $advertisement->document->delete();
        }

        return (new AdvertisementResource($advertisement))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Advertisement $advertisement)
    {
        abort_if(Gate::denies('advertisement_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisement->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
