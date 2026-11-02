<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\OutfitController;
use App\Http\Controllers\Api\PackingController;
use App\Http\Controllers\Api\WardrobeController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public Endpoints (Onboarding, Login & Registrasi)
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Protected Endpoints (Wajib Bearer Token Sanctum dari Flutter)
    Route::middleware('auth:sanctum')->group(function () {
        // 1.0 Kelola Akun, Profil & Notifikasi
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile/password', [AuthController::class, 'updatePassword']);
        Route::get('/notifications', [AuthController::class, 'notifications']);
        Route::patch('/notifications/{id}/read', [AuthController::class, 'markNotificationRead']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // 2.0 Kelola Digital Wardrobe & AI Auto-Tagging
        Route::get('/categories', [WardrobeController::class, 'categories']);
        Route::get('/wardrobe/items', [WardrobeController::class, 'index']);
        Route::post('/wardrobe/auto-tag', [WardrobeController::class, 'uploadAndAutoTag']);
        Route::post('/wardrobe/items', [WardrobeController::class, 'store']);
        Route::delete('/wardrobe/items/{id}', [WardrobeController::class, 'destroy']);

        // 3.0 Susun Outfit, AI OOTD Curator & Packing List
        Route::get('/outfits', [OutfitController::class, 'index']);
        Route::post('/outfits', [OutfitController::class, 'store']);
        Route::post('/stylist/curate', [OutfitController::class, 'generateAiOutfit']);
        Route::get('/recommendations/daily', [OutfitController::class, 'dailyRecommendations']);

        Route::get('/packing-lists', [PackingController::class, 'index']);
        Route::post('/packing-lists', [PackingController::class, 'store']);
        Route::patch('/packing-lists/{packingId}/items/{itemId}', [PackingController::class, 'togglePacked']);

        // 4.0 Kelola Kalender OOTD & Weather Forecast
        Route::get('/calendar', [CalendarController::class, 'index']);
        Route::post('/calendar', [CalendarController::class, 'store']);
    });
});