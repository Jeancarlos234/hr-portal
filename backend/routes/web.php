<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'app'     => 'RRHH API',
        'version' => '1.0.0',
        'status'  => 'ok',
    ]);
});

Route::get('/login', function () {
    return response()->json([
        'success' => false,
        'message' => 'No autenticado.',
        'errors'  => [],
    ], 401);
})->name('login');