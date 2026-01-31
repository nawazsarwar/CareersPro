<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAdvertisementRequest;
use App\Http\Requests\StoreAdvertisementRequest;
use App\Http\Requests\UpdateAdvertisementRequest;
use App\Models\Advertisement;
use App\Models\AdvertisementType;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class AdvertisementsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('advertisement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisements = Advertisement::with(['type', 'added_by', 'approved_by', 'media'])->get();

        $advertisement_types = AdvertisementType::get();

        $users = User::get();

        return view('frontend.advertisements.index', compact('advertisement_types', 'advertisements', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('advertisement_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $types = AdvertisementType::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $added_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $approved_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.advertisements.create', compact('added_bies', 'approved_bies', 'types'));
    }

    public function store(StoreAdvertisementRequest $request)
    {
        $advertisement = Advertisement::create($request->all());

        if ($request->input('document', false)) {
            $advertisement->addMedia(storage_path('tmp/uploads/' . basename($request->input('document'))))->toMediaCollection('document');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $advertisement->id]);
        }

        return redirect()->route('frontend.advertisements.index');
    }

    public function edit(Advertisement $advertisement)
    {
        abort_if(Gate::denies('advertisement_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $types = AdvertisementType::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $added_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $approved_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $advertisement->load('type', 'added_by', 'approved_by');

        return view('frontend.advertisements.edit', compact('added_bies', 'advertisement', 'approved_bies', 'types'));
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

        return redirect()->route('frontend.advertisements.index');
    }

    public function show(Advertisement $advertisement)
    {
        abort_if(Gate::denies('advertisement_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisement->load('type', 'added_by', 'approved_by');

        return view('frontend.advertisements.show', compact('advertisement'));
    }

    public function destroy(Advertisement $advertisement)
    {
        abort_if(Gate::denies('advertisement_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisement->delete();

        return back();
    }

    public function massDestroy(MassDestroyAdvertisementRequest $request)
    {
        $advertisements = Advertisement::find(request('ids'));

        foreach ($advertisements as $advertisement) {
            $advertisement->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('advertisement_create') && Gate::denies('advertisement_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Advertisement();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
