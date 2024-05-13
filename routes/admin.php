<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCartController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminRegisterController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminImageController;
use Inertia\Inertia;

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
    Route::get('/category/show/{id}', [AdminCategoryController::class, 'show'])->name('category.show');
    Route::get('/category/edit/{id}', [AdminCategoryController::class, 'edit'])->name('category.edit');
    Route::put('/category/update/{id}', [AdminCategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/delete/{id}', [AdminCategoryController::class, 'destroy'])->name('category.destroy');

    Route::get('/product', [AdminProductController::class, 'index'])->name('product.index');
    Route::get('/product/register', [AdminProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [AdminProductController::class, 'store'])->name('product.store');
    Route::get('/product/show/{id}', [AdminProductController::class, 'show'])->name('product.show');
    Route::get('/product/edit/{id}', [AdminProductController::class, 'edit'])->name('product.edit');
    Route::put('/product/update/{id}', [AdminProductController::class, 'update'])->name('product.update');
    Route::delete('/product/delete/{id}', [AdminProductController::class, 'destroy'])->name('product.destroy');

    Route::delete('/image/delete/{id}', [AdminImageController::class, 'destroy'])->name('image.destroy');
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function (){
    Route::post('logout', [AdminLoginController::class, 'destroy'])
    ->name('logout');

    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [AdminProfileController::class, 'destroy'])->name('profile.destroy');
});

