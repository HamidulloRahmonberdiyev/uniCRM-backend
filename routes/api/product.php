<?php

use App\Http\Controllers\Api\Product\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('products')->group(function () {
        Route::get('/list', [ProductController::class, 'list']);
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/store', [ProductController::class, 'store']);
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::put('/update/{product}', [ProductController::class, 'update']);
        Route::delete('/delete/{product}', [ProductController::class, 'destroy']);
    });
});
