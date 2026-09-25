<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// RUTAS PÚBLICAS (sin autenticación)
// ==========================================
Route::prefix('auth')->group(function () {
    Route::post('login',            [AuthController::class, 'login']);
    Route::post('register',         [AuthController::class, 'register']);
    Route::post('forgot-password',  [AuthController::class, 'forgotPassword']);
    Route::post('reset-password',   [AuthController::class, 'resetPassword']);
});

// ==========================================
// RUTAS PROTEGIDAS (Sanctum)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me',      [AuthController::class, 'me']);
    });

    // Endpoint de prueba
    Route::get('/user', function (\Illuminate\Http\Request $request) {
        return $request->user();
    });
});