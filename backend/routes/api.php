<?php

use Illuminate\Support\Facades\Route;

// =====================================================
// ZONE DEV A : AUTH, USERS, DASHBOARD, NOTIFICATIONS
// =====================================================

Route::post('/register',
    [App\Http\Controllers\AuthController::class, 'register']);

Route::post('/login',
    [App\Http\Controllers\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',
        [App\Http\Controllers\AuthController::class, 'logout']);

    Route::get('/profile',
        [App\Http\Controllers\AuthController::class, 'profile']);

    Route::put('/profile',
        [App\Http\Controllers\AuthController::class, 'updateProfile']);
});

// =====================================================
// ZONE DEV B : PROJETS, COLONNES, TACHES, COMMENTAIRES
// =====================================================

// Les routes de Dev B 