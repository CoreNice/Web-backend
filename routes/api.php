<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileCMSController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\HealthController;

Route::prefix('v1')->group(function () {
    Route::get('/health/db', [HealthController::class, 'database']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/registerAdmin', [AuthController::class, 'registerAdmin']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index']);
    Route::get('/activities', [\App\Http\Controllers\ActivityController::class, 'index']);
    Route::get('/profile-cms', [ProfileCMSController::class, 'index']);
    Route::get('/profile-cms/{id}', [ProfileCMSController::class, 'show']);

    Route::middleware('auth.api')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/profile/update', [ProfileController::class, 'update']);
    });

    // admin product management (butuh token + admin role)
    Route::middleware(['auth.api', 'admin'])->prefix('admin')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::get('/products/{id}', [ProductController::class, 'show']);
        Route::post('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // admin activity management
        Route::get('/activities', [ActivityController::class, 'index']);
        Route::post('/activities', [ActivityController::class, 'store']);
        Route::get('/activities/{id}', [ActivityController::class, 'show']);
        Route::post('/activities/{id}', [ActivityController::class, 'update']);
        Route::delete('/activities/{id}', [ActivityController::class, 'destroy']);

        // admin user management
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::post('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        // admin supplier management
        Route::get('/suppliers', [SupplierController::class, 'index']);
        Route::post('/suppliers', [SupplierController::class, 'store']);
        Route::get('/suppliers/{id}', [SupplierController::class, 'show']);
        Route::post('/suppliers/{id}', [SupplierController::class, 'update']);
        Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy']);

        // admin profile cms management
        Route::get('/profile-cms', [\App\Http\Controllers\Admin\ProfileCMSController::class, 'index']);
        Route::post('/profile-cms', [\App\Http\Controllers\Admin\ProfileCMSController::class, 'store']);
        Route::get('/profile-cms/{id}', [\App\Http\Controllers\Admin\ProfileCMSController::class, 'show']);
        Route::post('/profile-cms/{id}', [\App\Http\Controllers\Admin\ProfileCMSController::class, 'update']);
        Route::delete('/profile-cms/{id}', [\App\Http\Controllers\Admin\ProfileCMSController::class, 'destroy']);

        // admin site settings (simple key/value store)
        Route::get('/site-settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'index']);
        Route::get('/site-settings/{key}', [\App\Http\Controllers\Admin\SiteSettingController::class, 'show']);
        Route::post('/site-settings/{key}', [\App\Http\Controllers\Admin\SiteSettingController::class, 'update']);
        Route::delete('/site-settings/{key}', [\App\Http\Controllers\Admin\SiteSettingController::class, 'destroy']);
    });
});
