<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * Contrôleur de profil utilisateur
 *
 * Gère:
 * - Affichage du profil (public et privé)
 * - Mise à jour du profil
 * - Upload d'avatar
 * - Changement de mot de passe
 * - Suppression de compte (soft delete)
 */
class ProfileController extends Controller
{
    /**
     * GET /api/profile
     * Récupérer le profil de l'utilisateur connecté
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        // Charger les articles récents
        $recentPosts = $user->posts()
            ->with('category')
            ->withCount(['likes', 'comments'])
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'user' => $user,
            'recent_posts' => $recentPosts,
        ], Response::HTTP_OK);
    }

    /**
     * GET /api/users/{username}
     * Récupérer le profil public d'un utilisateur par son username
     */
    public function publicProfile(string $username): JsonResponse
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé',
            ], Response::HTTP_NOT_FOUND);
        }

        // Données publiques uniquement
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'avatar' => $user->avatar,
                'avatar_url' => $user->avatar_url,
                'bio' => $user->bio,
                'location' => $user->location,
                'created_at' => $user->created_at,
                'posts_count' => $user->posts_count,
                'comments_count' => $user->comments_count,
                'last_post_date' => $user->last_post_date,
            ],
        ], Response::HTTP_OK);
    }

    /**
     * GET /api/users/{username}/posts
     * Récupérer les posts d'un utilisateur
     */
    public function userPosts(string $username): JsonResponse
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé',
            ], Response::HTTP_NOT_FOUND);
        }

        $posts = $user->posts()
            ->with(['category'])
            ->withCount('likes')
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $posts,
        ], Response::HTTP_OK);
    }

    /**
     * PUT /api/profile
     * Mettre à jour le profil de l'utilisateur connecté
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Gérer l'upload de l'avatar
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar s'il existe
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Stocker le nouvel avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        // Gérer la suppression de l'avatar
        if ($request->boolean('remove_avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = null;
        }

        // Vérifier si l'email a changé
        $emailChanged = isset($validated['email']) && $validated['email'] !== $user->email;

        // Mettre à jour l'utilisateur
        $user->update($validated);

        // Si l'email a changé, réinitialiser la vérification
        if ($emailChanged) {
            $user->email_verified_at = null;
            $user->save();
            // TODO: Envoyer un email de vérification
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil mis a jour avec succes',
            'user' => $user->fresh(),
        ], Response::HTTP_OK);
    }

    /**
     * PUT /api/profile/password
     * Changer le mot de passe
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $user = $request->user();

        $user->update([
            'password' => $request->validated('password'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe modifié avec succès',
        ], Response::HTTP_OK);
    }

    /**
     * DELETE /api/profile
     * Supprimer le compte (soft delete)
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = $request->user();

        // Verifier le mot de passe
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mot de passe incorrect',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Supprimer l'avatar
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Revoquer tous les tokens
        $user->tokens()->delete();

        // Soft delete de l'utilisateur
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Compte supprime avec succes',
        ], Response::HTTP_OK);
    }

    /**
     * POST /api/profile/avatar
     * Upload d'avatar séparé (optionnel)
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = $request->user();

        // Supprimer l'ancien avatar
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Stocker le nouvel avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Avatar mis a jour avec succes',
            'avatar' => $path,
            'avatar_url' => $user->fresh()->avatar_url,
        ], Response::HTTP_OK);
    }

    /**
     * DELETE /api/profile/avatar
     * Supprimer l'avatar
     */
    public function deleteAvatar(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Avatar supprime avec succes',
            'avatar_url' => $user->fresh()->avatar_url,
        ], Response::HTTP_OK);
    }
}
