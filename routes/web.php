<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\RedirectAuthenticatedUsersController;
use App\Http\Controllers\{
    ProductController,
    CategoryController,
    AdditionController
};

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::inertia('/dashboard', 'Dashboard')->name('dashboard');

    Route::get("/redirectAuthenticatedUsers", [RedirectAuthenticatedUsersController::class, "home"]);

    Route::group(['middleware' => 'checkRole:admin'], function() {
        Route::inertia('/admin/dashboard', 'Admin/Dashboard')->name('adminDashboard');
    });
    Route::group(['middleware' => 'checkRole:user'], function() {
        Route::inertia('/user/dashboard', 'User/Dashboard')->name('userDashboard');
    });
    
    Route::group(['middleware' => 'checkRole:client'], function() {
        
        Route::prefix('client')->group(function () {
            Route::inertia('/dashboard', 'Client/Dashboard')->name('clientDashboard');

            Route::prefix('products')->group(function () {
                Route::get('/', [ProductController::class, 'index'])->name('client.products');
                Route::get('/create', [ProductController::class, 'create'])->name('products.create');
                Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
                Route::post('/', [ProductController::class, 'store'])->name('products.store');
                Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
                Route::post('/{id}', [ProductController::class, 'update'])->name('products.update');
            });

            Route::prefix('category')->group(function () {
                Route::get('/', [CategoryController::class, 'index'])->name('category.index');
                Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
                Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
                Route::post('/', [CategoryController::class, 'store'])->name('category.store');
                Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');
                Route::post('/{id}', [CategoryController::class, 'update'])->name('category.update');
            });

            Route::prefix('addition')->group(function () {
                Route::get('/', [AdditionController::class, 'index'])->name('addition.index');
                Route::get('/create', [AdditionController::class, 'create'])->name('addition.create');
                Route::get('/{addition}/edit', [AdditionController::class, 'edit'])->name('addition.edit');
                Route::post('/', [AdditionController::class, 'store'])->name('addition.store');
                Route::delete('/{addition}', [AdditionController::class, 'destroy'])->name('addition.destroy');
                Route::post('/{id}', [AdditionController::class, 'update'])->name('addition.update');
            });

            //Route::get('/category', [CategoryController::class, 'index'])->name('client.category');
            //Route::get('/addition', [ProductController::class, 'index'])->name('client.addition');
            Route::get('/freight', [ProductController::class, 'index'])->name('client.freight');
        });
        
    });
});

require __DIR__.'/auth.php';
