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
    Route::get('/posts/{post}/liked_post', 'likedPost');
})->middleware(['auth:sanctum']);

Route::controller(PostImageController::class)->group(function () {
    Route::post('/posts/images', 'store');
})->middleware(['auth:sanctum']);

Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index');
    Route::get('/users/{user}/posts', 'posts');
    Route::get('/users/{user}/toggle_following', 'toggleFollowing');
})->middleware(['auth:sanctum']);
