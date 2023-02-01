<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBoardRequest;
use App\Http\Requests\StoreBoardRequest;
use App\Http\Requests\UpdateBoardRequest;
use App\Models\Board;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BoardsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('board_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $boards = Board::all();

        return view('frontend.boards.index', compact('boards'));
    }

    public function create()
    {
        abort_if(Gate::denies('board_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.boards.create');
    }

    public function store(StoreBoardRequest $request)
    {
        $board = Board::create($request->all());

        return redirect()->route('frontend.boards.index');
    }

    public function edit(Board $board)
    {
        abort_if(Gate::denies('board_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.boards.edit', compact('board'));
    }

    public function update(UpdateBoardRequest $request, Board $board)
    {
        $board->update($request->all());

        return redirect()->route('frontend.boards.index');
    }

    public function show(Board $board)
    {
        abort_if(Gate::denies('board_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.boards.show', compact('board'));
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
