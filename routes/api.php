<?php

use App\Http\Controllers\Api\v1\PostController;
use App\Http\Controllers\Api\v1\PostImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(PostController::class)->group(function () {
    Route::post('/posts', 'store');
})->middleware(['auth:sanctum']);

Route::controller(PostImageController::class)->group(function () {
    Route::post('/posts/images', 'store');
})->middleware(['auth:sanctum']);
