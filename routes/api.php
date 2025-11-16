<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HealthController;



// Route::get('/products', [ProductController::class, 'index']);
// Route::post('/admin/products', [ProductController::class, 'store']);
Route::prefix('v1')->group(function () {
    Route::get('/mongodb', [HealthController::class, 'database']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/registerAdmin', [AuthController::class, 'registerAdmin']);
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index']);
    Route::middleware('auth.api')->group(function () {
        Route::get('/me',      [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update']);
    });

    // admin product management (butuh token + admin role)
    Route::middleware(['auth.api', 'admin'])->prefix('admin')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::get('/products/{id}', [ProductController::class, 'show']);
        Route::post('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
        
        // admin user management
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::post('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });
});
