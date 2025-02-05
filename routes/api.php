<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\SorterController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('profile', [AuthController::class, 'profile']);
    });
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('dashboard')->group(function () {
        Route::get('stats', [DashboardController::class, 'stats']);
        Route::get('orders', [DashboardController::class, 'orders']);
    });

    Route::prefix('settings')->group(function () {
        Route::get('sorters', [SorterController::class, 'index']);
        Route::post('sorters/store', [SorterController::class, 'store']);
        Route::delete('sorters/delete/{sorter}', [SorterController::class, 'destroy']);
    });

    Route::get('customers/search', [CustomerController::class, 'search']);
    Route::get('customers', [CustomerController::class, 'index']);
    Route::get('customers/{customer}', [CustomerController::class, 'show']);
    Route::post('customers/store', [CustomerController::class, 'store']);
    Route::put('customers/update/{customer}', [CustomerController::class, 'update']);
    Route::delete('customers/delete/{customer}', [CustomerController::class, 'destroy']);

    Route::get('orders/stats', [OrderController::class, 'stats']);
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{order}', [OrderController::class, 'show']);
    Route::post('orders/store', [OrderController::class, 'store']);
    Route::put('orders/update/{order}', [OrderController::class, 'update']);
    Route::delete('orders/delete/{order}', [OrderController::class, 'destroy']);
    Route::put('orders/change-status/{order}', [OrderController::class, 'changeStatus']);

    Route::get('regions', [RegionController::class, 'index']);
});
