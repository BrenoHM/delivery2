<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RedirectAuthenticatedUsersController;
use App\Http\Controllers\{
    ProductController,
    CategoryController,
    AdditionController,
    DashboardController,
    FreightController,
    GerencianetController,
    OrderController
};
use App\Http\Controllers\Site\{
    CheckoutController,
    FinishedController,
    HomeController,
    NotificationController
};
use Illuminate\Support\Facades\Session;

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


//Rotas dominio raiz
Route::domain(env('APP_URL'))->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('site.index');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('site.checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'addCart'])->name('site.checkout');
    Route::delete('/checkout', [CheckoutController::class, 'removeCart'])->name('cart.delete');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('site.checkout.process');
    Route::get('/convert-slug', [CheckoutController::class, 'convertSlug'])->name('site.checkout.convert.slug');
    Route::get('/finished', [FinishedController::class, 'index'])->name('site.finished');
    Route::post('/notification', [NotificationController::class, 'receiveNotification'])->name('site.notification');

    //rotas provisorias
    Route::get('session', function(){
        return Session::all();
    });

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        //plans
        Route::post('/plan/cancel', [GerencianetController::class, 'cancelPlan'])->name('plan.cancel');

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
                //Route::inertia('/dashboard', 'Client/Dashboard')->name('clientDashboard');
                Route::get('/dashboard', [DashboardController::class, 'client'])->name('clientDashboard');

                Route::prefix('orders')->group(function () {
                    Route::get('/', [OrderController::class, 'index'])->name('order.index');
                    Route::patch('/{order}/{status_order_id}', [OrderController::class, 'changeStatus'])->name('order.changeStatus');
                });

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

                Route::prefix('freight')->group(function () {
                    Route::get('/', [FreightController::class, 'index'])->name('freight.index');
                    Route::get('/create', [FreightController::class, 'create'])->name('freight.create');
                    Route::get('/{freight}/edit', [FreightController::class, 'edit'])->name('freight.edit');
                    Route::post('/', [FreightController::class, 'store'])->name('freight.store');
                    Route::delete('/{freight}', [FreightController::class, 'destroy'])->name('freight.destroy');
                    Route::put('/{freight}', [FreightController::class, 'update'])->name('freight.update');
                });
            });
        });

    });

    require __DIR__.'/auth.php';

});

Route::get('tb', [ProductController::class, 'tb_variants']);
Route::get('testGerencianetSubscriber', [GerencianetController::class, 'testGerencianetSubscriber']);
Route::get('createOneStepBillet', [GerencianetController::class, 'createOneStepBillet']);
Route::get('getNotification/{token}', [GerencianetController::class, 'getNotification']);

//Rotas para tenants ficar??o separadas
Route::domain('{tenant}.' . env('APP_URL'))
    ->middleware(['getTennant'])
    ->group(base_path('routes/tenant.php'));


// Route::middleware(['getTennant'])->group(function () { ... });

