<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\UserController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\Mobile\OrderController as MobileOrderController;
use App\Http\Controllers\Api\Mobile\SupplierController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReturnedController;
use App\Http\Controllers\Api\Settings\BottleController;
use App\Http\Controllers\Api\Settings\CustomerTypeController;
use App\Http\Controllers\Api\Settings\DistrictController;
use App\Http\Controllers\Api\Settings\NeighborhoodController;
use App\Http\Controllers\Api\Settings\PriceController;
use App\Http\Controllers\Api\Settings\RegionController;
use App\Http\Controllers\Api\Settings\RoleController;
use App\Http\Controllers\Api\Settings\SourceController;
use App\Http\Controllers\Api\StatsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh-token', [AuthController::class, 'refreshToken']);

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
        Route::get('prices/show', [PriceController::class, 'show']);
        Route::post('prices/store', [PriceController::class, 'store']);
        Route::put('prices/update/{price}', [PriceController::class, 'update']);
        Route::delete('prices/delete/{price}', [PriceController::class, 'destroy']);

        Route::get('customer-types', [CustomerTypeController::class, 'index']);
        Route::post('customer-types/store', [CustomerTypeController::class, 'store']);
        Route::delete('customer-types/delete/{customerType}', [CustomerTypeController::class, 'destroy']);

        Route::get('regions', [RegionController::class, 'index']);
        Route::get('regions/details', [RegionController::class, 'region_details']);
        Route::post('regions/{region}/change-status', [RegionController::class, 'change_status']);

        Route::prefix('neighborhoods')->group(function () {
            Route::get('/', [NeighborhoodController::class, 'index']);
            Route::post('store', [NeighborhoodController::class, 'store']);
            Route::put('update/{neighborhood}', [NeighborhoodController::class, 'update']);
            Route::delete('delete/{neighborhood}', [NeighborhoodController::class, 'destroy']);
        });

        Route::get('districts', [DistrictController::class, 'index']);
        Route::get('districts/{district}', [DistrictController::class, 'show']);
        Route::post('districts/{district}/change-status', [DistrictController::class, 'change_status']);

        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('/{user}', [UserController::class, 'show']);
            Route::post('store', [UserController::class, 'store']);
            Route::put('update/{user}', [UserController::class, 'update']);
            Route::delete('delete/{user}', [UserController::class, 'destroy']);
        });

        Route::prefix('roles')->group(function () {
            Route::get('list', [RoleController::class, 'roles']);
        });
    });

    Route::get('customers/find-phone', [CustomerController::class, 'findCustomerByPhone']);
    Route::get('customers/stats', [CustomerController::class, 'stats']);
    Route::get('customers/search', [CustomerController::class, 'search']);
    Route::get('customers', [CustomerController::class, 'index']);
    Route::get('customers/{customer}', [CustomerController::class, 'show']);
    Route::post('customers/store', [CustomerController::class, 'store']);
    Route::put('customers/update/{customer}', [CustomerController::class, 'update']);
    Route::delete('customers/delete/{customer}', [CustomerController::class, 'destroy']);
    Route::get('customers/{customer}/order-history', [CustomerController::class, 'order_history']);

    Route::get('orders/stats', [OrderController::class, 'stats']);
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{order}', [OrderController::class, 'show']);
    Route::post('orders/store', [OrderController::class, 'store']);
    Route::put('orders/update/{order}', [OrderController::class, 'update']);
    Route::delete('orders/delete/{order}', [OrderController::class, 'destroy']);
    Route::put('orders/change-status/{order}', [OrderController::class, 'changeStatus']);

    Route::prefix('mobile')->group(function () {

        Route::prefix('supplier')->group(function () {
            Route::get('stats', [SupplierController::class, 'supplierStats']);
        });

        Route::prefix('orders')->group(function () {
            Route::get('actives', [MobileOrderController::class, 'activeOrders']);
            Route::get('booked', [MobileOrderController::class, 'bookedOrders']);
            Route::get('history', [MobileOrderController::class, 'orderHistory']);
            Route::put('booking/{order}', [MobileOrderController::class, 'bookingOrder']);
            Route::put('activate/{order}', [MobileOrderController::class, 'activateOrder']);
        });
    });

    Route::prefix('stats')->group(function () {
        Route::get('monthly-orders', [StatsController::class, 'monthlyOrderChart']);
        Route::get('customer-type', [StatsController::class, 'customerTypeChart']);
        Route::get('order-source', [StatsController::class, 'orderSourceChart']);
        Route::get('supplier-orders', [StatsController::class, 'supplierOrdersChart']);
    });

    Route::get('returneds', [ReturnedController::class, 'index']);
    Route::post('returneds/store', [ReturnedController::class, 'store']);
    Route::put('returneds/update/{returned}', [ReturnedController::class, 'update']);
    Route::delete('returneds/delete/{returned}', [ReturnedController::class, 'destroy']);
});

require_once 'api/monitoring.php';
require_once 'api/product.php';
