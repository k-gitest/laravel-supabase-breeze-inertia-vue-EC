<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\OrderController;

Route::middleware('auth')->group(function (){
    Route::get('/cart/index', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
    Route::get('/cart/edit/{id}', [CartController::class, 'edit'])->name('cart.edit');
    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/destroy/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::get('/order/detail/{id}', [OrderController::class, 'show'])->name('order.show');
});

Route::post('stripe/webhook', [WebhookController::class, 'handleWebhook']);

Route::get('/category/index', [CategoryController::class, 'index'])->name('category.index');
Route::get('/category/show/{id}', [CategoryController::class, 'show'])->name('category.show');

Route::get('/product', [ProductController::class, 'index'])->name('product.index');
Route::get('/product/show/{id}', [ProductController::class, 'show'])->name('product.show');
