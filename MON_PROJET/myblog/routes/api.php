<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;

// ==================== ROUTES PUBLIQUES ====================

// Authentification
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

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
});
