<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyQualificationLevelRequest;
use App\Http\Requests\StoreQualificationLevelRequest;
use App\Http\Requests\UpdateQualificationLevelRequest;
use App\Models\QualificationLevel;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class QualificationLevelsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('qualification_level_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = QualificationLevel::query()->select(sprintf('%s.*', (new QualificationLevel())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'qualification_level_show';
                $editGate = 'qualification_level_edit';
                $deleteGate = 'qualification_level_delete';
                $crudRoutePart = 'qualification-levels';

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
            $table->editColumn('value', function ($row) {
                return $row->value ? $row->value : '';
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

        return view('admin.qualificationLevels.index');
    }

    public function create()
    {
        abort_if(Gate::denies('qualification_level_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.qualificationLevels.create');
    }

    public function store(StoreQualificationLevelRequest $request)
    {
        $qualificationLevel = QualificationLevel::create($request->all());

        return redirect()->route('admin.qualification-levels.index');
    }

    public function edit(QualificationLevel $qualificationLevel)
    {
        abort_if(Gate::denies('qualification_level_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.qualificationLevels.edit', compact('qualificationLevel'));
    }

    public function update(UpdateQualificationLevelRequest $request, QualificationLevel $qualificationLevel)
    {
        $qualificationLevel->update($request->all());

        return redirect()->route('admin.qualification-levels.index');
    }

    public function show(QualificationLevel $qualificationLevel)
    {
        abort_if(Gate::denies('qualification_level_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.qualificationLevels.show', compact('qualificationLevel'));
    }

    public function destroy(QualificationLevel $qualificationLevel)
    {
        abort_if(Gate::denies('qualification_level_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $qualificationLevel->delete();

        return back();
    }

    public function massDestroy(MassDestroyQualificationLevelRequest $request)
    {
        QualificationLevel::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
