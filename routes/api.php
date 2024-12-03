<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;



Route::post('/login', [AuthController::class, 'login']);


// Routes for both admin and user (accessible by both after authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    // Product view (both users and admins can view the product)
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/allproducts', [ProductController::class, 'index']);
});

// Routes for admin only (accessible only by admins after authentication)
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // CRUD operations for products (only for admins)
    Route::resource('products', ProductController::class);
});
