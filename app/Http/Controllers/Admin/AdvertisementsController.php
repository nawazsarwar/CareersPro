<?php

namespace App\Http\Controllers\Admin;

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
use Yajra\DataTables\Facades\DataTables;

class AdvertisementsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('advertisement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Advertisement::with(['type', 'added_by', 'approved_by'])->select(sprintf('%s.*', (new Advertisement)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'advertisement_show';
                $editGate      = 'advertisement_edit';
                $deleteGate    = 'advertisement_delete';
                $crudRoutePart = 'advertisements';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('slug', function ($row) {
                return $row->slug ? $row->slug : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });

            $table->addColumn('type_title', function ($row) {
                return $row->type ? $row->type->title : '';
            });

            $table->editColumn('advertisement_url', function ($row) {
                return $row->advertisement_url ? $row->advertisement_url : '';
            });
            $table->editColumn('document', function ($row) {
                return $row->document ? '<a href="' . $row->document->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('default_fee', function ($row) {
                return $row->default_fee ? $row->default_fee : '';
            });

            $table->editColumn('status', function ($row) {
                return $row->status ? $row->status : '';
            });
            $table->editColumn('remarks', function ($row) {
                return $row->remarks ? $row->remarks : '';
            });
            $table->addColumn('added_by_name', function ($row) {
                return $row->added_by ? $row->added_by->name : '';
            });

            $table->addColumn('approved_by_name', function ($row) {
                return $row->approved_by ? $row->approved_by->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'type', 'document', 'added_by', 'approved_by']);

            return $table->make(true);
        }

        $advertisement_types = AdvertisementType::get();
        $users               = User::get();

        return view('admin.advertisements.index', compact('advertisement_types', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('advertisement_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $types = AdvertisementType::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $added_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $approved_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.advertisements.create', compact('added_bies', 'approved_bies', 'types'));
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

        return redirect()->route('admin.advertisements.index');
    }

    public function edit(Advertisement $advertisement)
    {
        abort_if(Gate::denies('advertisement_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $types = AdvertisementType::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $added_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $approved_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $advertisement->load('type', 'added_by', 'approved_by');

        return view('admin.advertisements.edit', compact('added_bies', 'advertisement', 'approved_bies', 'types'));
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

        return redirect()->route('admin.advertisements.index');
    }

    public function show(Advertisement $advertisement)
    {
        abort_if(Gate::denies('advertisement_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisement->load('type', 'added_by', 'approved_by');

        return view('admin.advertisements.show', compact('advertisement'));
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
