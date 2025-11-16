<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\PasswordResetController;


Route::prefix('v1')->group(function () {
    Route::get('/health/db', [HealthController::class, 'database']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/product/{id}', [ProductController::class, 'show']);
    Route::get('/health', function () {
        return response()->json(['status' => 'OK']);
    });
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::post('/auth/forgot-password', [PasswordResetController::class, 'forgot']);
    Route::post('/auth/verify-reset-token', [PasswordResetController::class, 'verifyToken']);
    Route::post('/auth/reset-password', [PasswordResetController::class, 'reset']);
});

// Route::middleware('jwt.verify')->group(function () {
// Route::middleware('auth:api')->group(function () {
//     Route::prefix('v1')->group(function () {
//         Route::get('/me', [AuthController::class, 'me']);
//         Route::post('/logout', [AuthController::class, 'logout']);

//         // Cart
//         Route::get('/cart', [CartController::class, 'get']);
//         Route::post('/cart', [CartController::class, 'update']);
//         Route::delete('/cart', [CartController::class, 'clear']);

//         // Order
//         Route::post('/checkout', [OrderController::class, 'create']);

//         // Admin
//         Route::post('/product', [ProductController::class, 'store']);
//         Route::put('/product/{id}', [ProductController::class, 'update']);
//         Route::delete('/product/{id}', [ProductController::class, 'destroy']);
//     });
// });
