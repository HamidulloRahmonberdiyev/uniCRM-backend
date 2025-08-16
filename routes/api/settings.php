<?php

use App\Http\Controllers\Api\Auth\UserController;
use App\Http\Controllers\Api\Settings\BottleController;
use App\Http\Controllers\Api\Settings\CustomerTypeController;
use App\Http\Controllers\Api\Settings\DistrictController;
use App\Http\Controllers\Api\Settings\NeighborhoodController;
use App\Http\Controllers\Api\Settings\PriceController;
use App\Http\Controllers\Api\Settings\RegionController;
use App\Http\Controllers\Api\Settings\RoleController;
use App\Http\Controllers\Api\Settings\SourceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
  Route::prefix('settings')->group(function () {

    Route::prefix('sources')->group(function () {
      Route::get('/', [SourceController::class, 'index']);
      Route::post('store', [SourceController::class, 'store']);
      Route::put('update/{source}', [SourceController::class, 'update']);
      Route::delete('delete/{source}', [SourceController::class, 'destroy']);
    });

    Route::prefix('bottles')->group(function () {
      Route::get('/', [BottleController::class, 'index']);
      Route::post('store', [BottleController::class, 'store']);
      Route::put('update/{bottle}', [BottleController::class, 'update']);
      Route::delete('delete/{bottle}', [BottleController::class, 'destroy']);
    });

    Route::prefix('prices')->group(function () {
      Route::get('/', [PriceController::class, 'index']);
      Route::get('show', [PriceController::class, 'show']);
      Route::post('store', [PriceController::class, 'store']);
      Route::put('update/{price}', [PriceController::class, 'update']);
      Route::delete('delete/{price}', [PriceController::class, 'destroy']);
    });

    Route::prefix('customer-types')->group(function () {
      Route::get('/', [CustomerTypeController::class, 'index']);
      Route::post('store', [CustomerTypeController::class, 'store']);
      Route::delete('delete/{customerType}', [CustomerTypeController::class, 'destroy']);
    });

    Route::prefix('regions')->group(function () {
      Route::get('/', [RegionController::class, 'index']);
      Route::get('details', [RegionController::class, 'region_details']);
      Route::post('/{region}/change-status', [RegionController::class, 'change_status']);
    });

    Route::prefix('neighborhoods')->group(function () {
      Route::get('/', [NeighborhoodController::class, 'index']);
      Route::post('store', [NeighborhoodController::class, 'store']);
      Route::put('update/{neighborhood}', [NeighborhoodController::class, 'update']);
      Route::delete('delete/{neighborhood}', [NeighborhoodController::class, 'destroy']);
    });

    Route::prefix('districts')->group(function () {
      Route::get('/', [DistrictController::class, 'index']);
      Route::get('/{district}', [DistrictController::class, 'show']);
      Route::post('/{district}/change-status', [DistrictController::class, 'change_status']);
    });

    Route::prefix('users')->group(function () {
      Route::get('/', [UserController::class, 'index']);
      Route::get('/{user}', [UserController::class, 'show']);
      Route::post('store', [UserController::class, 'store']);
      Route::put('update/{user}', [UserController::class, 'update']);
      Route::delete('delete/{user}', [UserController::class, 'destroy']);
    });

    Route::prefix('roles')->group(function () {
      Route::get('list', [RoleController::class, 'roles']);
      Route::get('index', [RoleController::class, 'index']);
      Route::post('store', [RoleController::class, 'store']);
      Route::put('update/{role}', [RoleController::class, 'update']);
      Route::delete('delete/{role}', [RoleController::class, 'destroy']);

      Route::post('/{role}/assign-permissions', [RoleController::class, 'assignPermissions']);
      Route::post('/{role}/sync-permissions', [RoleController::class, 'syncPermissions']);
    });
  });
});
