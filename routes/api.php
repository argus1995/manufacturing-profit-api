<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectCostController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\OperationalCostController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('products', ProductController::class);
Route::apiResource('productions', ProductionController::class);
Route::apiResource('sales', SaleController::class);
Route::apiResource('direct-costs', DirectCostController::class);
Route::apiResource('operational-costs', OperationalCostController::class);
Route::get('/dashboard', [DashboardController::class, 'index']);
