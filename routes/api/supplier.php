<?php

use App\Http\Controllers\Api\Supplier\SupplierController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

  Route::prefix('suppliers')->group(function () {
    Route::get('/list', [SupplierController::class, 'getSuppliers']);
  });
});
