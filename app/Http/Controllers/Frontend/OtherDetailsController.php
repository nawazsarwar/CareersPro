<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyOtherDetailRequest;
use App\Http\Requests\StoreOtherDetailRequest;
use App\Http\Requests\UpdateOtherDetailRequest;
use App\Models\OtherDetail;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class OtherDetailsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('other_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $otherDetails = OtherDetail::with(['user', 'media'])->get();

        $users = User::get();

        return view('frontend.otherDetails.index', compact('otherDetails', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('other_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.otherDetails.create', compact('users'));
    }

    public function store(StoreOtherDetailRequest $request)
    {
        $otherDetail = OtherDetail::create($request->all());

        if ($request->input('remark_essential_qualification_document', false)) {
            $otherDetail->addMedia(storage_path('tmp/uploads/' . basename($request->input('remark_essential_qualification_document'))))->toMediaCollection('remark_essential_qualification_document');
        }

        if ($request->input('remark_desirable_qualification_document', false)) {
            $otherDetail->addMedia(storage_path('tmp/uploads/' . basename($request->input('remark_desirable_qualification_document'))))->toMediaCollection('remark_desirable_qualification_document');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $otherDetail->id]);
        }

        return redirect()->route('frontend.other-details.index');
    }

    public function edit(OtherDetail $otherDetail)
    {
        abort_if(Gate::denies('other_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $otherDetail->load('user');

        return view('frontend.otherDetails.edit', compact('otherDetail', 'users'));
    }

    public function update(UpdateOtherDetailRequest $request, OtherDetail $otherDetail)
    {
        $otherDetail->update($request->all());

        if ($request->input('remark_essential_qualification_document', false)) {
            if (! $otherDetail->remark_essential_qualification_document || $request->input('remark_essential_qualification_document') !== $otherDetail->remark_essential_qualification_document->file_name) {
                if ($otherDetail->remark_essential_qualification_document) {
                    $otherDetail->remark_essential_qualification_document->delete();
                }
                $otherDetail->addMedia(storage_path('tmp/uploads/' . basename($request->input('remark_essential_qualification_document'))))->toMediaCollection('remark_essential_qualification_document');
            }
        } elseif ($otherDetail->remark_essential_qualification_document) {
            $otherDetail->remark_essential_qualification_document->delete();
        }

        if ($request->input('remark_desirable_qualification_document', false)) {
            if (! $otherDetail->remark_desirable_qualification_document || $request->input('remark_desirable_qualification_document') !== $otherDetail->remark_desirable_qualification_document->file_name) {
                if ($otherDetail->remark_desirable_qualification_document) {
                    $otherDetail->remark_desirable_qualification_document->delete();
                }
                $otherDetail->addMedia(storage_path('tmp/uploads/' . basename($request->input('remark_desirable_qualification_document'))))->toMediaCollection('remark_desirable_qualification_document');
            }
        } elseif ($otherDetail->remark_desirable_qualification_document) {
            $otherDetail->remark_desirable_qualification_document->delete();
        }

        return redirect()->route('frontend.other-details.index');
    }

    public function show(OtherDetail $otherDetail)
    {
        abort_if(Gate::denies('other_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $otherDetail->load('user');

        return view('frontend.otherDetails.show', compact('otherDetail'));
    }

    public function destroy(OtherDetail $otherDetail)
    {
        abort_if(Gate::denies('other_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $otherDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroyOtherDetailRequest $request)
    {
        $otherDetails = OtherDetail::find(request('ids'));

        foreach ($otherDetails as $otherDetail) {
            $otherDetail->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('other_detail_create') && Gate::denies('other_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new OtherDetail();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
