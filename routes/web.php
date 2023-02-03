<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\RedirectAuthenticatedUsersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

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
                Route::post('/create', [ProductController::class, 'store'])->name('products.store');
                //Route::delete('/pets/{id}', [PetController::class, 'destroy'])->name('pet.destroy');
                Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
                Route::post('/{id}', [ProductController::class, 'update'])->name('products.update');
            });

            Route::get('/category', [CategoryController::class, 'index'])->name('client.category');
            Route::get('/addition', [ProductController::class, 'index'])->name('client.addition');
            Route::get('/freight', [ProductController::class, 'index'])->name('client.freight');
        });
        
    });
});

require __DIR__.'/auth.php';
