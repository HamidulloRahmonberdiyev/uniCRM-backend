<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['jwt_or_basic'])->group(function () {

  Route::prefix('orders')->group(function () {
    Route::get('stats', [OrderController::class, 'stats']);
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{order}', [OrderController::class, 'show']);
    Route::post('store', [OrderController::class, 'store']);
    Route::put('update/{order}', [OrderController::class, 'update']);
    Route::delete('delete/{order}', [OrderController::class, 'destroy']);
    Route::put('change-status/{order}', [OrderController::class, 'changeStatus']);
  });

  Route::prefix('orders')->group(function () {
    Route::post('/{order}/change-order-action', [OrderController::class, 'changeOrderAction']);
    Route::post('/{order}/attach-to-supplier', [OrderController::class, 'attachOrderToSupplier']);
  });
});
