<?php

use App\Http\Controllers\Api\v1\PostController;
use App\Http\Controllers\Api\v1\PostImageController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(PostController::class)->group(function () {
    Route::get('/posts', 'index');
    Route::post('/posts', 'store');
    Route::post('/posts/{post}/liked_post', 'likedPost');
    Route::post('/posts/{post}/repost', 'repost');
    Route::post('/posts/{post}/comment', 'commentStore');
    Route::get('/posts/{post}/comment', 'commentsList');
})->middleware(['auth:sanctum']);

Route::controller(PostImageController::class)->group(function () {
    Route::post('/posts/images', 'store');
})->middleware(['auth:sanctum']);

Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index');
    Route::get('/users/{user}/posts', 'posts');
    Route::post('/users/{user}/toggle_following', 'toggleFollowing');
    Route::get('/users/following_posts', 'followingPosts');
    Route::post('/users/stats', 'stat');
})->middleware(['auth:sanctum']);
