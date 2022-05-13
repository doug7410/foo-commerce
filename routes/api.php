<?php

use App\Http\Middleware\FiltersMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/products', [App\Http\Controllers\Api\ProductsController::class, 'store']);
    Route::post('/products/{product}', [App\Http\Controllers\Api\ProductsController::class, 'update']);
    Route::delete('/products/{product}', [App\Http\Controllers\Api\ProductsController::class, 'delete']);

    Route::group(['middleware' => FiltersMiddleware::class], function () {
        Route::get('/products', [App\Http\Controllers\Api\ProductsController::class, 'index']);
        Route::get('/inventory', [App\Http\Controllers\Api\InventoryController::class, 'index']);
        Route::get('/orders', [App\Http\Controllers\Api\OrdersController::class, 'index']);
    });

    Route::get('/orders/sales-report', [App\Http\Controllers\Api\OrdersController::class, 'salesReport']);
});

