<?php

use App\Http\Controllers\Api\Supplier\SupplierController;
use Illuminate\Support\Facades\Route;

Route::middleware(['jwt_or_basic'])->group(function () {

  Route::prefix('suppliers')->group(function () {
    Route::get('/list', [SupplierController::class, 'getSuppliers']);
  });
});
