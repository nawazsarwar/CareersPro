<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBoardRequest;
use App\Http\Requests\StoreBoardRequest;
use App\Http\Requests\UpdateBoardRequest;
use App\Models\Board;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class BoardsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('board_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Board::query()->select(sprintf('%s.*', (new Board())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'board_show';
                $editGate = 'board_edit';
                $deleteGate = 'board_delete';
                $crudRoutePart = 'boards';

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

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.boards.index');
    }

    public function create()
    {
        abort_if(Gate::denies('board_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.boards.create');
    }

    public function store(StoreBoardRequest $request)
    {
        $board = Board::create($request->all());

        return redirect()->route('admin.boards.index');
    }

    public function edit(Board $board)
    {
        abort_if(Gate::denies('board_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.boards.edit', compact('board'));
    }

    public function update(UpdateBoardRequest $request, Board $board)
    {
        $board->update($request->all());

        return redirect()->route('admin.boards.index');
    }

    public function show(Board $board)
    {
        abort_if(Gate::denies('board_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.boards.show', compact('board'));
    }

    public function destroy(Board $board)
    {
        abort_if(Gate::denies('board_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $board->delete();

        return back();
    }

    public function massDestroy(MassDestroyBoardRequest $request)
    {
        Board::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
