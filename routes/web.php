<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;




Route::get('/login', function () {
    return view('login');
})->name('login');


Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Handle login submission

Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth'])->group(function () {
    Route::get('/products', [ProductController::class, 'list'])->name('products.list');
    Route::get('/products/{id}/details', [ProductController::class, 'details'])->name('products.details');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::post('/products/{id}/update', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    });
});
