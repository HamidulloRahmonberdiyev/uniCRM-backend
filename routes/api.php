<?php

use App\Http\Controllers\Api\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/customers', [CustomerController::class, 'index']);
    Route::post('/customers/{customer}', [CustomerController::class, 'show']);
    Route::post('/customers/store', [CustomerController::class, 'store']);
    Route::put('/customers/update/{customer}', [CustomerController::class, 'update']);
    Route::delete('/customers/delete/{customer}', [CustomerController::class, 'destroy']);
});
