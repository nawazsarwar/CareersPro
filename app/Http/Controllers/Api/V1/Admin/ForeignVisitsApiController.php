<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreForeignVisitRequest;
use App\Http\Requests\UpdateForeignVisitRequest;
use App\Http\Resources\Admin\ForeignVisitResource;
use App\Models\ForeignVisit;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForeignVisitsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('foreign_visit_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ForeignVisitResource(ForeignVisit::with(['country', 'user'])->get());
    }

    public function store(StoreForeignVisitRequest $request)
    {
        $foreignVisit = ForeignVisit::create($request->all());

        return (new ForeignVisitResource($foreignVisit))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ForeignVisit $foreignVisit)
    {
        abort_if(Gate::denies('foreign_visit_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ForeignVisitResource($foreignVisit->load(['country', 'user']));
    }

    public function update(UpdateForeignVisitRequest $request, ForeignVisit $foreignVisit)
    {
        $foreignVisit->update($request->all());

        return (new ForeignVisitResource($foreignVisit))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ForeignVisit $foreignVisit)
    {
        abort_if(Gate::denies('foreign_visit_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $foreignVisit->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
