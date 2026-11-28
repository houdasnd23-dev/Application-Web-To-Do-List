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

    Route::get('/dashboard',
        [App\Http\Controllers\DashboardController::class, 'index']);

    Route::get('/notifications',
        [App\Http\Controllers\NotificationController::class, 'index']);

    Route::get('/notifications/unread-count',
        [App\Http\Controllers\NotificationController::class, 'unreadCount']);

    Route::patch('/notifications/read-all',
        [App\Http\Controllers\NotificationController::class, 'markAllAsRead']);

    Route::patch('/notifications/{id}/read',
        [App\Http\Controllers\NotificationController::class, 'markAsRead']);


    Route::middleware('role:admin')->group(function () {
        Route::get('/users',
            [App\Http\Controllers\UserController::class, 'index']);

        Route::put('/users/{user}/role',
            [App\Http\Controllers\UserController::class, 'updateRole']);

        Route::delete('/users/{user}',
            [App\Http\Controllers\UserController::class, 'destroy']);
    });
});

// =====================================================
// ZONE DEV B : PROJETS, COLONNES, TACHES, COMMENTAIRES
// =====================================================

// Les routes de Dev B 