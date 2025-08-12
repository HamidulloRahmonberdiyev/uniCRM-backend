<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

  Route::prefix('orders')->group(function () {
    Route::post('/{order}/change-order-action', [OrderController::class, 'changeOrderAction']);
    Route::post('/{order}/attach-to-supplier', [OrderController::class, 'attachOrderToSupplier']);
  });
});
