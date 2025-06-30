<?php

use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['web'])->group(function () {
    Route::get('/auth/google', [AuthController::class, 'requestToGoogle']);
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
});



Route::middleware('auth:sanctum')->group(function () {
    // User Routes
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/search', [PostController::class, 'search']);
    Route::get('/posts/liked', [PostController::class, 'likedPosts']);
    Route::get('/posts/commented', [PostController::class, 'commentedPosts']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
    Route::post('/posts/{post}/like', [LikeController::class, 'likePost']);
    Route::delete('/posts/{post}/unlike', [LikeController::class, 'unlikePost']);

    // Admin Routes
    Route::middleware('admin')->group(function () {
        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{post}', [PostController::class, 'update']);
        Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    });
});