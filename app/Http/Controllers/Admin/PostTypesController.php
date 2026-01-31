<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPostTypeRequest;
use App\Http\Requests\StorePostTypeRequest;
use App\Http\Requests\UpdatePostTypeRequest;
use App\Models\PostType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PostTypesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('post_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = PostType::query()->select(sprintf('%s.*', (new PostType)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'post_type_show';
                $editGate      = 'post_type_edit';
                $deleteGate    = 'post_type_delete';
                $crudRoutePart = 'post-types';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('pdf_template', function ($row) {
                return $row->pdf_template ? $row->pdf_template : '';
            });
            $table->editColumn('admit_card_template', function ($row) {
                return $row->admit_card_template ? $row->admit_card_template : '';
            });
            $table->editColumn('interview_letter_template', function ($row) {
                return $row->interview_letter_template ? $row->interview_letter_template : '';
            });
            $table->editColumn('submission_venue', function ($row) {
                return $row->submission_venue ? $row->submission_venue : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? $row->status : '';
            });
            $table->editColumn('remarks', function ($row) {
                return $row->remarks ? $row->remarks : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.postTypes.index');
    }

    public function create()
    {
        abort_if(Gate::denies('post_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.postTypes.create');
    }

    public function store(StorePostTypeRequest $request)
    {
        $postType = PostType::create($request->all());

        return redirect()->route('admin.post-types.index');
    }

    public function edit(PostType $postType)
    {
        abort_if(Gate::denies('post_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.postTypes.edit', compact('postType'));
    }

    public function update(UpdatePostTypeRequest $request, PostType $postType)
    {
        $postType->update($request->all());

        return redirect()->route('admin.post-types.index');
    }

    public function show(PostType $postType)
    {
        abort_if(Gate::denies('post_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.postTypes.show', compact('postType'));
    }

    public function destroy(PostType $postType)
    {
        abort_if(Gate::denies('post_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $postType->delete();

        return back();
    }

    public function massDestroy(MassDestroyPostTypeRequest $request)
    {
        $postTypes = PostType::find(request('ids'));

        foreach ($postTypes as $postType) {
            $postType->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
