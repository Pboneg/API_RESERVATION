<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NotificationController;

// Routes pour l'authentification
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class,'resetPassword']);

// Routes pour les utilisateurs
Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'create']); // Créer un nouvel utilisateur
    Route::get('/', [UserController::class, 'index']); // Lister tous les utilisateurs
    Route::get('/{id}', [UserController::class, 'view']); // Afficher un utilisateur spécifique
    Route::put('/{id}', [UserController::class, 'update']); // Mettre à jour un utilisateur
    Route::delete('/{id}', [UserController::class, 'delete']); // Supprimer un utilisateur
});

// Routes pour les événements
Route::prefix('events')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [EventController::class, 'store']); // Créer un nouvel événement
    Route::get('/', [EventController::class, 'index']); // Lister tous les événements
    Route::get('/{id}', [EventController::class, 'view']); // Afficher un événement spécifique
    Route::put('/{id}', [EventController::class, 'update']); // Mettre à jour un événement
    Route::delete('/{id}', [EventController::class, 'delete']); // Supprimer un événement
});

// Routes pour les réservations
Route::prefix('bookings')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [BookingController::class, 'store']); // Créer une nouvelle réservation
    Route::get('/', [BookingController::class, 'index']); // Lister toutes les réservations
    Route::get('/{id}', [BookingController::class, 'view']); // Afficher une réservation spécifique
    Route::put('/{id}', [BookingController::class, 'update']); // Mettre à jour une réservation
    Route::delete('/{id}', [BookingController::class, 'delete']); // Supprimer une réservation
});


Route::middleware('auth:sanctum')->group(function () {

    // Créer une nouvelle notification
    Route::post('/notifications',[NotificationController::class, 'sendNotification']);

    // Récupérer toutes les notifications
    Route::get('/notifications', [NotificationController::class, 'index']);

    // Marquer une notification comme lue
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    Route::post('/activate', [AuthController::class,'verify']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Route pour les notifications
    Route::post('/notifications',[NotificationController::class, 'sendNotification']);
    Route::get('/notifications',[NotificationController::class, 'index']);
});
