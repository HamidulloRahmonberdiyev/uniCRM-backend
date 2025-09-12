<?php

use App\Http\Controllers\Api\Monitoring\OrderMonitoringController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group(function () {

    Route::prefix('monitoring')->group(function () {
        Route::get('orders', [OrderMonitoringController::class, 'index']);
    });
});
