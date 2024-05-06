<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
Route::get('/cart/edit', [CartController::class, 'edit'])->name('cart.edit');
Route::delete('/cart/destroy/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('/category/index', [CategoryController::class, 'index'])->name('category.index');
Route::get('/category/register', [CategoryController::class, 'create'])->name('category.create');
Route::post('/category/register', [CategoryController::class, 'store'])->name('category.store');
Route::get('/category/show/{id}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
Route::put('/category/update/{id}', [CategoryController::class, 'update'])->name('category.update');
Route::delete('/category/delete/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

Route::get('/product', [ProductController::class, 'index'])->name('product.index');
Route::get('/product/register', [ProductController::class, 'create'])->name('product.create');
Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
Route::get('/product/show/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
Route::put('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
Route::delete('/product/delete/{id}', [ProductController::class, 'destroy'])->name('product.destroy');