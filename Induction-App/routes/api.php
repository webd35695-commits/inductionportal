<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\ChatController;


    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::get('/applications/{id}', [ApplicationController::class, 'show']);
    Route::delete('/applications/{id}', [ApplicationController::class, 'destroy']);
    Route::get('/applications/export', [ApplicationController::class, 'export']);
Route::PATCH('/applications/{id}/status', [ApplicationController::class, 'updateStatus']);

// Public test route for debugging
Route::get('/applications/public', [ApplicationController::class, 'index']);




Route::middleware('auth:sanctum')->group(function () {
    Route::post('/send-admin-message', [ChatController::class, 'sendAdminMessage']);
    Route::post('/send-candidate-message', [ChatController::class, 'sendCandidateMessage']);
});
