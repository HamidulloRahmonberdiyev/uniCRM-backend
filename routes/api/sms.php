<?php

use App\Http\Controllers\Api\Settings\Sms\EskizController;
use App\Http\Controllers\Api\Settings\Sms\SmsTemplateController;
use App\Http\Controllers\Api\Settings\Sms\SmsTemplateTagController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('sms')->group(function () {

  Route::prefix('eskiz')->group(function () {
    Route::post('send', [EskizController::class, 'send']);
    Route::post('send-by-filter', [EskizController::class, 'sendByFilter']);
  });

  Route::prefix('template-tags')->group(function () {
    Route::get('/', [SmsTemplateTagController::class, 'getAll']);
  });

  Route::prefix('templates')->group(function () {
    Route::get('/types', [SmsTemplateController::class, 'getTemplateTypes']);

    Route::get('/', [SmsTemplateController::class, 'index']);
    Route::get('/{template}', [SmsTemplateController::class, 'show']);
    Route::post('/store', [SmsTemplateController::class, 'store']);
    Route::put('/update/{template}', [SmsTemplateController::class, 'update']);
    Route::delete('/delete/{template}', [SmsTemplateController::class, 'destroy']);
  });
});
