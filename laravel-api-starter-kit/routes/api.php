<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Public routes
    Route::get('/', function () {
        return app(App\Helpers\ApiResponse::class)->success([
            'name' => 'Laravel API Starter Kit',
            'version' => '1.0.0',
        ], 'Welcome to Laravel API Starter Kit.');
    });

    Route::get('/health', function () {
        return app(App\Helpers\ApiResponse::class)->success([
            'status' => 'ok',
            'version' => '1.0.0',
        ], 'API is healthy.');
    });

    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])
            ->middleware('throttle:5,1');
        Route::post('/login', [AuthController::class, 'login'])
            ->middleware('throttle:10,1');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
            ->middleware('throttle:5,1');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])
            ->middleware('throttle:5,1');

        // Protected auth routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);
        });
    });

    // User routes
    Route::middleware(['auth:sanctum', 'permission:users.view'])->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
    });

    Route::middleware(['auth:sanctum', 'permission:users.create'])->group(function () {
        Route::post('/users', [UserController::class, 'store']);
    });

    Route::middleware(['auth:sanctum', 'permission:users.update'])->group(function () {
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::patch('/users/{id}', [UserController::class, 'update']);
        Route::post('/users/{id}/restore', [UserController::class, 'restore']);
    });

    Route::middleware(['auth:sanctum', 'permission:users.delete'])->group(function () {
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });

    // Role routes
    Route::middleware(['auth:sanctum', 'permission:roles.view'])->group(function () {
        Route::get('/roles', [RoleController::class, 'index']);
        Route::get('/roles/{id}', [RoleController::class, 'show']);
    });

    Route::middleware(['auth:sanctum', 'permission:roles.create'])->group(function () {
        Route::post('/roles', [RoleController::class, 'store']);
    });

    Route::middleware(['auth:sanctum', 'permission:roles.update'])->group(function () {
        Route::put('/roles/{id}', [RoleController::class, 'update']);
        Route::patch('/roles/{id}', [RoleController::class, 'update']);
    });

    Route::middleware(['auth:sanctum', 'permission:roles.delete'])->group(function () {
        Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
    });

    // Permission routes
    Route::middleware(['auth:sanctum', 'permission:permissions.view'])->group(function () {
        Route::get('/permissions', [PermissionController::class, 'index']);
        Route::get('/permissions/{id}', [PermissionController::class, 'show']);
    });

    Route::middleware(['auth:sanctum', 'permission:permissions.create'])->group(function () {
        Route::post('/permissions', [PermissionController::class, 'store']);
    });

    Route::middleware(['auth:sanctum', 'permission:permissions.update'])->group(function () {
        Route::put('/permissions/{id}', [PermissionController::class, 'update']);
        Route::patch('/permissions/{id}', [PermissionController::class, 'update']);
    });

    Route::middleware(['auth:sanctum', 'permission:permissions.delete'])->group(function () {
        Route::delete('/permissions/{id}', [PermissionController::class, 'destroy']);
    });
});