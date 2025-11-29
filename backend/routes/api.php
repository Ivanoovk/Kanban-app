<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\ListController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\AttachmentController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {

    // Boards
    Route::get('/boards', [BoardController::class, 'getBoards']);
    Route::post('/boards', [BoardController::class, 'create']);
    Route::put('/boards/{id}', [BoardController::class, 'update']);
    Route::delete('/boards/{id}', [BoardController::class, 'delete']);
    Route::get('/boards/{id}', [BoardController::class, 'showBoard']);

    // Lists
    Route::get('/boards/{id}/lists', [ListController::class, 'getColumns']);
    Route::post('/boards/{id}/lists', [ListController::class, 'create']);
    Route::put('/lists/{id}', [ListController::class, 'update']);
    Route::delete('/lists/{id}', [ListController::class, 'delete']);

    //Tasks
    Route::get('/lists/{id}/tasks', [TaskController::class, 'getTasks']);
    Route::post('/lists/{id}/tasks', [TaskController::class, 'create']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'delete']);

    //Attachments
    Route::get('/tasks/{taskId}/attachments', [AttachmentController::class, 'showAttachments']);
    Route::post('/tasks/{taskId}/attachments', [AttachmentController::class, 'upload']);
    Route::delete('/attachments/{id}', [AttachmentController::class, 'delete']);

});