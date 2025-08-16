<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\Mobile\OrderController as MobileOrderController;
use App\Http\Controllers\Api\Mobile\SupplierController;
use App\Http\Controllers\Api\ReturnedController;
use App\Http\Controllers\Api\StatsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('dashboard')->group(function () {
        Route::get('stats', [DashboardController::class, 'stats']);
        Route::get('orders', [DashboardController::class, 'orders']);
    });

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

require_once 'api/auth.php';
require_once 'api/monitoring.php';
require_once 'api/product.php';
require_once 'api/supplier.php';
require_once 'api/order.php';
require_once 'api/customer.php';
require_once 'api/settings.php';
