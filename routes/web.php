<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function (){
    Route::get('/products', [App\Http\Controllers\ProductsController::class, 'index'])->name('products');
    Route::get('/inventory', [App\Http\Controllers\InventoryController::class, 'index'])->name('inventory');
    Route::get('/orders', [App\Http\Controllers\OrdersController::class, 'index'])->name('orders');
});
