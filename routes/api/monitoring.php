<?php

use App\Http\Controllers\Api\Monitoring\OrderMonitoringController;
use Illuminate\Support\Facades\Route;

Route::middleware(['jwt_or_basic'])->group(function () {

    Route::prefix('monitoring')->group(function () {
        Route::get('orders', [OrderMonitoringController::class, 'index']);
    });
});
