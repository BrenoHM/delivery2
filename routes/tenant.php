<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tenant\HomeController;
use Illuminate\Foundation\Application;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index']);

//show product
Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');