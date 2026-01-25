<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminController;

// ==================== ROUTES PUBLIQUES ====================

// Authentification
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Google OAuth
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Posts
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/search', [PostController::class, 'search']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::get('/posts/{id}/comments', [CommentController::class, 'index']);

// Catégories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{slug}/posts', [CategoryController::class, 'posts']);

// Profil public
Route::get('/users/{username}', [ProfileController::class, 'publicProfile']);
Route::get('/users/{username}/posts', [ProfileController::class, 'userPosts']);

// ==================== ROUTES PROTÉGÉES ====================

Route::middleware('auth:sanctum')->group(function () {

    // === Authentification ===
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // === Profil utilisateur ===
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);           // Voir mon profil
        Route::put('/', [ProfileController::class, 'update']);         // Modifier mon profil
        Route::put('/password', [ProfileController::class, 'updatePassword']); // Changer mot de passe
        Route::delete('/', [ProfileController::class, 'destroy']);     // Supprimer mon compte
        Route::post('/avatar', [ProfileController::class, 'uploadAvatar']);    // Upload avatar
        Route::delete('/avatar', [ProfileController::class, 'deleteAvatar']);  // Supprimer avatar
    });

    // === Posts ===
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);
    Route::get('/my-posts', [PostController::class, 'myPosts']);

    // === Catégories (admin) ===
    Route::post('/categories', [CategoryController::class, 'store']);

    // === Likes ===
    Route::post('/posts/{id}/like', [LikeController::class, 'toggle']);
    Route::get('/posts/{id}/likes', [LikeController::class, 'getLikes']);

    // === Commentaires ===
    Route::post('/posts/{id}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

    // NOUVEAU : Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);

    // ROUTES ADMIN (protégées par middleware admin)
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // Statistiques
    Route::get('/stats', [AdminController::class, 'stats']);

    // Gestion des utilisateurs
    Route::get('/users', [AdminController::class, 'users']);
    Route::put('/users/{id}/toggle-admin', [AdminController::class, 'toggleAdmin']);
    Route::put('/users/{id}/toggle-ban', [AdminController::class, 'toggleBan']);

    // Gestion des articles
    Route::get('/posts', [AdminController::class, 'posts']);
    Route::delete('/posts/{id}', [AdminController::class, 'deletePost']);

    // Gestion des commentaires
    Route::get('/comments', [AdminController::class, 'comments']);
    Route::delete('/comments/{id}', [AdminController::class, 'deleteComment']);

    // Gestion des catégories
    Route::get('/categories/stats', [AdminController::class, 'categoriesStats']);
    Route::put('/categories/{id}', [AdminController::class, 'updateCategory']);
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory']);
});
});
