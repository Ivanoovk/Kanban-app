<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attachment;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{


    public function showAttachments($taskId)
    {
        return Attachment::where('task_id', $taskId)->get();
    }


    public function upload(Request $request, $taskId)
    {
        $request->validate([
            'file' => 'required|file|max:10240' // 10MB
        ]);

        $task = Task::findOrFail($taskId);

        //Saves the file in storage/public/attachments
        $file = $request->file('file');
        $path = $file->store('attachments', 'public');

        $attachment = Attachment::create([
            'task_id'    => $task->id,
            'file_path'  => $path,
            'file_name'  => $file->getClientOriginalName(),
            'file_type'  => $file->getMimeType(),
            'file_size'  => $file->getSize(), // bytes
        ]);

        return response()->json($attachment, 201);
    }


    public function delete($id)
    {
        $attachment = Attachment::findOrFail($id);

        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return response()->json(['message' => 'File removed']);
    }
}