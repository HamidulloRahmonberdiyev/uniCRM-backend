<?php

use App\Http\Controllers\Api\Product\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['jwt_or_basic'])->group(function () {

    Route::prefix('products')->group(function () {
        Route::get('/list', [ProductController::class, 'list']);
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/store', [ProductController::class, 'store']);
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::post('/update/{product}', [ProductController::class, 'update']);
        Route::delete('/delete/{product}', [ProductController::class, 'destroy']);
    });
});
