<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReturnedController;
use App\Http\Controllers\Api\Settings\BottleController;
use App\Http\Controllers\Api\Settings\CityController;
use App\Http\Controllers\Api\Settings\CustomerTypeController;
use App\Http\Controllers\Api\Settings\PriceController;
use App\Http\Controllers\Api\Settings\RegionController;
use App\Http\Controllers\Api\Settings\SourceController;
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
        Route::get('sources', [SourceController::class, 'index']);
        Route::post('sources/store', [SourceController::class, 'store']);
        Route::put('sources/update/{source}', [SourceController::class, 'update']);
        Route::delete('sources/delete/{source}', [SourceController::class, 'destroy']);

        Route::get('bottles', [BottleController::class, 'index']);
        Route::post('bottles/store', [BottleController::class, 'store']);
        Route::put('bottles/update/{bottle}', [BottleController::class, 'update']);
        Route::delete('bottles/delete/{bottle}', [BottleController::class, 'destroy']);

        Route::get('prices', [PriceController::class, 'index']);
        Route::post('prices/store', [PriceController::class, 'store']);
        Route::put('prices/update/{price}', [PriceController::class, 'update']);
        Route::delete('prices/delete/{price}', [PriceController::class, 'destroy']);

        Route::get('customer-types', [CustomerTypeController::class, 'index']);
        Route::post('customer-types/store', [CustomerTypeController::class, 'store']);
        Route::delete('customer-types/delete/{customerType}', [CustomerTypeController::class, 'destroy']);

        Route::get('regions', [RegionController::class, 'index']);

        Route::get('cities', [CityController::class, 'index']);
        Route::post('cities/store', [CityController::class, 'store']);
        Route::put('cities/update/{city}', [CityController::class, 'update']);
        Route::delete('cities/delete/{city}', [CityController::class, 'destroy']);
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

    Route::get('returneds', [ReturnedController::class, 'index']);
    Route::post('returneds/store', [ReturnedController::class, 'store']);
    Route::put('returneds/update/{returned}', [ReturnedController::class, 'update']);
    Route::delete('returneds/delete/{returned}', [ReturnedController::class, 'destroy']);
});
