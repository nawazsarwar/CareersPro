<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreOtherDetailRequest;
use App\Http\Requests\UpdateOtherDetailRequest;
use App\Http\Resources\Admin\OtherDetailResource;
use App\Models\OtherDetail;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OtherDetailsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('other_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OtherDetailResource(OtherDetail::with(['user'])->get());
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

        return (new OtherDetailResource($otherDetail))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(OtherDetail $otherDetail)
    {
        abort_if(Gate::denies('other_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OtherDetailResource($otherDetail->load(['user']));
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

        return (new OtherDetailResource($otherDetail))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(OtherDetail $otherDetail)
    {
        abort_if(Gate::denies('other_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $otherDetail->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
