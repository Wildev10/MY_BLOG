<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;

// Routes PUBLIQUES (pas besoin d'être connecté)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/posts', [PostController::class, 'index']); // Liste des articles accessible à tous
Route::get('/posts/search', [PostController::class, 'search']);
Route::get('/posts/{id}', [PostController::class, 'show']); // Voir un article accessible à tous
Route::get('/categories', [CategoryController::class, 'index']); // Liste des catégories
Route::get('/categories/{slug}/posts', [CategoryController::class, 'posts']); // Articles d'une catégorie

// Routes PROTÉGÉES (besoin d'un token)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Posts (actions nécessitant une connexion)
    Route::post('/posts', [PostController::class, 'store']); // Créer un article
    Route::put('/posts/{id}', [PostController::class, 'update']); // Modifier
    Route::delete('/posts/{id}', [PostController::class, 'destroy']); // Supprimer
    Route::get('/my-posts', [PostController::class, 'myPosts']); // Mes articles

    // Categories (admin uniquement)
    Route::post('/categories', [CategoryController::class, 'store']); // Créer une catégorie
});
