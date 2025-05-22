<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

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
// GET SINGLE POST
Route::get('/post/{id}', [PostController::class, 'getPost']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // ADD POST
    Route::post('/add-post', [PostController::class, 'addPost']);
    // EDIT POST
    Route::post('/edit-post', [PostController::class, 'editPost']);
    Route::post('/edit-post/{post_id}', [PostController::class, 'editPost2']);
    // DELETE POST
    Route::delete('/delete-post/{post_id}', [PostController::class, 'deletePost']);


    // ADD COMMENT
    Route::post('/comment', [CommentController::class, 'addComment']);
    // ADD LIKE
    Route::post('/like', [LikeController::class, 'addLike']);


});
