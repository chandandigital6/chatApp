<?php
use App\Http\Controllers\Api\AuthApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatApiController;
use Illuminate\Support\Facades\Broadcast;


Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthApiController::class, 'me']);
    Route::post('/logout', [AuthApiController::class, 'logout']);

    Route::post('/save-fcm-token', [AuthApiController::class, 'save']);
});



Route::middleware('auth:sanctum')->prefix('chat')->group(function () {

    // Super Admin ko sab users ki list
    Route::get('/users', [ChatApiController::class, 'users']);

    // Inbox / chat list
    Route::get('/inbox', [ChatApiController::class, 'inbox']);

    // Kisi user ke sath messages
    Route::get('/messages/{user}', [ChatApiController::class, 'messages']);

    // Message send
    Route::post('/send/{user}', [ChatApiController::class, 'send']);
});