<?php

use App\Http\Controllers\Api\CustomerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['jwt_or_basic'])->group(function () {

  Route::prefix('customers')->group(function () {
    Route::get('find-phone', [CustomerController::class, 'findCustomerByPhone']);
    Route::get('stats', [CustomerController::class, 'stats']);
    Route::get('search', [CustomerController::class, 'search']);
    Route::get('/', [CustomerController::class, 'index']);
    Route::get('/{customer}', [CustomerController::class, 'show']);
    Route::post('store', [CustomerController::class, 'store']);
    Route::put('update/{customer}', [CustomerController::class, 'update']);
    Route::delete('delete/{customer}', [CustomerController::class, 'destroy']);
    Route::get('/{customer}/order-history', [CustomerController::class, 'order_history']);
  });
});
