<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

//Authentification part
Route::post('auth/register', [ApiTokenController::class, 'register']);
Route::post('auth/login', [ApiTokenController::class, 'login']);
Route::middleware('auth:sanctum')->post('auth/me', [ApiTokenController::class, 'me']);
Route::middleware('auth:sanctum')->post('auth/logout', [ApiTokenController::class, 'logout']);


//to-dont-list part
Route::middleware('auth:sanctum')->get('todos', [TodoController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('todos/{id}', [TodoController::class, 'show']);
    Route::post('todos', [TodoController::class, 'create']);
    Route::put('todos/{id}', [TodoController::class, 'update']);
    Route::delete('/todos/{id}', [TodoController::class, 'destroy']);
});
