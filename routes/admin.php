<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCartController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminRegisterController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminImageController;
use App\Http\Controllers\Admin\AdminStockController;
use App\Http\Controllers\Admin\AdminWarehouseController;
use App\Http\Controllers\Admin\AdminSearchController;

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminLoginController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AdminLoginController::class, 'store'])->name('admin.login');

    Route::get('/admin/register', [AdminRegisterController::class, 'create'])->name('admin.register');
    Route::post('/admin/register', [AdminRegisterController::class, 'store'])->name('admin.register');
});

Route::get('/admin/dashboard', function () {
    return Inertia::render('Auth/Admin/Dashboard');
})->middleware(['auth:admin', 'verified'])->name('admin.dashboard');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth:admin', 'verified']], function (){
    Route::get('/cart/', [AdminCartController::class, 'index'])->name('cart.index');
    Route::post('/cart/store', [AdminCartController::class, 'store'])->name('cart.store');
    Route::get('/cart/edit/{id}', [AdminCartController::class, 'edit'])->name('cart.edit');
    Route::put('/cart/update/{id}', [AdminCartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/destroy/{id}', [AdminCartController::class, 'destroy'])->name('cart.destroy');
    
    Route::get('/category/index', [AdminCategoryController::class, 'index'])->name('category.index');
    Route::get('/category/register', [AdminCategoryController::class, 'create'])->name('category.create');
    Route::post('/category/register', [AdminCategoryController::class, 'store'])->name('category.store');
    Route::get('/category/edit', [AdminCategoryController::class, 'edit'])->name('category.edit');
    Route::put('/category/update/{id}', [AdminCategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/delete', [AdminCategoryController::class, 'destroy'])->name('category.destroy');

    Route::get('/product', [AdminProductController::class, 'index'])->name('product.index');
    Route::get('/product/register', [AdminProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [AdminProductController::class, 'store'])->name('product.store');
    Route::get('/product/show/{id}', [AdminProductController::class, 'show'])->name('product.show');
    Route::get('/product/edit/{id}', [AdminProductController::class, 'edit'])->name('product.edit');
    Route::put('/product/update/{id}', [AdminProductController::class, 'update'])->name('product.update');
    Route::delete('/product/delete/{id}', [AdminProductController::class, 'destroy'])->name('product.destroy');
    Route::delete('/product/bulk/delete', [AdminProductController::class, 'bulkDestroy'])->name('product.bulkDestroy');

    Route::delete('/image/delete/{id}', [AdminImageController::class, 'destroy'])->name('image.destroy');

    Route::post('/stock/store', [AdminStockController::class, 'store'])->name('stock.store');
    Route::get('/stock/show/{id}', [AdminStockController::class, 'show'])->name('stock.show');
    Route::put('/stock/update', [AdminStockController::class, 'update'])->name('stock.update');
    Route::delete('/stock/delete', [AdminStockController::class, 'destroy'])->name('stock.destroy');

    Route::get('/warehouse', [AdminWarehouseController::class, 'index'])->name('warehouse.index');
    Route::get('/warehouse/register', [AdminWarehouseController::class, 'create'])->name('warehouse.create');
    Route::post('/warehouse/store', [AdminWarehouseController::class, 'store'])->name('warehouse.store');
    Route::get('/warehouse/show/{id}', [AdminWarehouseController::class, 'show'])->name('warehouse.show');
    Route::get('/warehouse/edit/{id}', [AdminWarehouseController::class, 'edit'])->name('warehouse.edit');
    Route::put('/warehouse/update/{id}', [AdminWarehouseController::class, 'update'])->name('warehouse.update');
    Route::delete('/warehouse/delete/{id}', [AdminWarehouseController::class, 'destroy'])->name('warehouse.destroy');

    Route::get('/product/search', [AdminSearchController::class, 'index'])->name('search');
    
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function (){
    Route::post('logout', [AdminLoginController::class, 'destroy'])
    ->name('logout');

    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [AdminProfileController::class, 'destroy'])->name('profile.destroy');
});

