<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function getBoards(Request $request)
    {
        return Board::where('user_id', $request->user()->id)->get();
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $board = Board::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user()->id
        ]);



        $defaultColumns = ['To Do', 'In Progress', 'Review'];

        foreach ($defaultColumns as $name) {
            \App\Models\ListModel::create([
            'title' => $name,
            'board_id' => $board->id]);
        }

        return response()->json($board, 201);

    }

    public function update(Request $request, $id)
    {
        $board = Board::where('user_id', $request->user()->id)->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $board->update([
            'title' => $request->title,
            'description' => $request->description
        ]);

        return response()->json($board);
    }


    public function delete(Request $request, $id)
    {
        $board = Board::where('user_id', $request->user()->id)->findOrFail($id);

        $board->delete();

        return response()->json(['message' => 'Board deleted']);
    }


    public function showBoard(Request $request, $id)
    {
        return Board::where('user_id', $request->user()->id)->findOrFail($id);
    }


}