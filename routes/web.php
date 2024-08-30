<?php

use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('layouts.app');
});

Route::resource('categories', App\Http\Controllers\CategoryController::class);
Route::resource('products', App\Http\Controllers\ProductController::class);
Route::resource('orders', App\Http\Controllers\OrderController::class);
Route::get('orders-syncOrder', [App\Http\Controllers\OrderController::class, 'syncOrder'])->name('orders.syncOrder');
