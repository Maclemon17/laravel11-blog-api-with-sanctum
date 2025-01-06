<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', function () {
    return response()->json(['message' => 'Welcome to blog api']);
});

// AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// GET ALL POST
Route::get('/posts', [PostController::class, 'getAllPosts']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // ADD POST
    Route::post('/add-post', [PostController::class, 'addPost']);
    // EDIT POST
    Route::post('/edit-post', [PostController::class, 'editPost']);
});