<?php

use Inertia\Inertia;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoListController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::middleware('auth')->group(function () {
  Route::get('/todo', [TodoListController::class, 'index'])->name('todo.index');
  Route::get('/todo/register', [TodoListController::class, 'create'])->name('todo.register');
  Route::post('/todo/register', [TodoListController::class, 'store'])->name('todo.store');
  Route::get('/todo/edit/{id}', [TodoListController::class, 'edit'])->name('todo.edit');
  Route::put('/todo/edit/{id}', [TodoListController::class, 'update'])->name('todo.update');
  Route::delete('/todo/edit/{id}', [TodoListController::class, 'destroy'])->name('todo.destroy');
});
                                 
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/ec.php';
require __DIR__.'/admin.php';