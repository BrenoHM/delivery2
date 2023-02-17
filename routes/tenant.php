<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tenant\CartController;
use App\Http\Controllers\Tenant\HomeController;
use Illuminate\Foundation\Application;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index']);

//show product
Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');

Route::get('cart', [CartController::class, 'cartList'])->name('cart.list');
Route::post('cart', [CartController::class, 'addToCart'])->name('cart.store');
Route::post('update-cart', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('cart/remove', [CartController::class, 'removeCart'])->name('cart.remove');
Route::post('clear', [CartController::class, 'clearAllCart'])->name('cart.clear');