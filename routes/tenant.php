<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tenant\HomeController;
use Illuminate\Foundation\Application;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index']);