<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SuggestController;

Route::middleware('auth')->group(function (){
    Route::get('/cart/index', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
    Route::get('/cart/edit/{id}', [CartController::class, 'edit'])->name('cart.edit');
    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/destroy/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::get('/order/detail/{id}', [OrderController::class, 'show'])->name('order.show');

    Route::get('/comment', [CommentController::class, 'index'])->name('comment.index');
    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');
    Route::delete('/comment/destroy/{id}', [CommentController::class, 'destroy'])->name('comment.destroy');

    Route::get('/favorite', [FavoriteController::class, 'index'])->name('favorite.index');
    Route::post('/favorite', [FavoriteController::class, 'store'])->name('favorite.store');
    Route::delete('/favorite/{id}', [FavoriteController::class, 'destroy'])->name('favorite.destroy');
});

Route::post('stripe/webhook', [WebhookController::class, 'handleWebhook']);

Route::get('/product', [ProductController::class, 'index'])->name('product.index');
Route::get('/product/show/{id}', [ProductController::class, 'show'])->name('product.show');

Route::get('/product/search', [SearchController::class, 'index'])->name('search');
Route::get('/product/suggest', [SuggestController::class, 'suggest'])->name('suggest');