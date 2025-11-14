<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\GalleryLikeController;
use App\Http\Controllers\Api\GalleryCommentController;
use App\Http\Controllers\Api\GalleryDownloadController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Gallery API Routes - menggunakan web middleware untuk session auth
Route::middleware('web')->group(function () {
    Route::get('/galleries', [GalleryController::class, 'index']);
    Route::get('/galleries/categories', [GalleryController::class, 'categories']);
    Route::get('/galleries/{id}', [GalleryController::class, 'show']);
    Route::post('/galleries', [GalleryController::class, 'store']);
    Route::put('/galleries/{id}', [GalleryController::class, 'update']);
    Route::delete('/galleries/{id}', [GalleryController::class, 'destroy']);

    // Like/Dislike Routes (Require Authentication)
    Route::middleware(['auth'])->group(function () {
        Route::post('/galleries/{gallery}/like', [GalleryLikeController::class, 'toggle']);
    });
    Route::get('/galleries/{gallery}/like-status', [GalleryLikeController::class, 'getStatus']);

    // Comment Routes
    Route::get('/galleries/{gallery}/comments', [GalleryCommentController::class, 'index']);
    Route::middleware(['auth'])->group(function () {
        Route::post('/galleries/{gallery}/comments', [GalleryCommentController::class, 'store']);
        Route::put('/galleries/{gallery}/comments/{comment}', [GalleryCommentController::class, 'update']);
        Route::delete('/galleries/{gallery}/comments/{comment}', [GalleryCommentController::class, 'destroy']);
    });

    // Download Routes (Require Authentication)
    Route::middleware(['auth'])->group(function () {
        Route::post('/galleries/{gallery}/download', [GalleryDownloadController::class, 'download']);
    });
});
