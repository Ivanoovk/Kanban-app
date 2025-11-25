<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ListModel;
use App\Models\Board;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function getColumns($boardId)
    {
        return ListModel::where('board_id', $boardId)->get();
    }


    public function create(Request $request, $boardId)
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        //check if board is owned by user
        $board = Board::where('user_id', $request->user()->id)
            ->findOrFail($boardId);

        return ListModel::create([
            'title' => $request->title,
            'board_id' => $board->id
        ]);
    }


    public function update(Request $request, $id)
    {
        $column = ListModel::findOrFail($id);

        $column->update([
            'title' => $request->title,
        ]);

        return response()->json($column);
    }



    public function delete(Request $request, $id)
    {
        $column = ListModel::findOrFail($id);
        $column->delete();

        return response()->json(['message' => 'Column deleted']);
    }

}