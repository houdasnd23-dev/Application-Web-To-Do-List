<?php

use Illuminate\Support\Facades\Route;

// =====================================================
// ZONE DEV A : AUTH, USERS, DASHBOARD, NOTIFICATIONS
// =====================================================

Route::post('/register',
    [App\Http\Controllers\AuthController::class, 'register']);

Route::post('/login',
    [App\Http\Controllers\AuthController::class, 'login']);

// =====================================================
// ZONE DEV B : PROJETS, COLONNES, TACHES, COMMENTAIRES
// =====================================================

// Les routes de Dev B 