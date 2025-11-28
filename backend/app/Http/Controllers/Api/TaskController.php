<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\ListModel;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function getTasks(Request $request, $listId)
    {
        return Task::where('list_id', $listId)
            ->orderBy('order')
            ->orderBy('id')
            ->get();
    }


    public function create(Request $request, $listId)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'tags' => 'nullable|string'
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'tags' => $request->tags,
            'list_id' => $listId,
            'order' => Task::where('list_id', $listId)->max('order') + 1,
        ]);

        return response()->json($task, 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'tags' => 'nullable|string',
            'list_id' => 'sometimes|integer|exists:lists,id',
            'order' => 'sometimes|integer',
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    public function delete(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted']);
    }

}