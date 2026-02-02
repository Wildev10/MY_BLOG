<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine si l'utilisateur peut voir tous les posts
     */
    public function viewAny(?User $user): bool
    {
        return true; // Tout le monde peut voir les posts
    }

    /**
     * Determine si l'utilisateur peut voir un post
     */
    public function view(?User $user, Post $post): bool
    {
        // Les posts publiés sont visibles par tous
        if ($post->status === 'published') {
            return true;
        }

        // Les brouillons ne sont visibles que par leur auteur
        return $user && $user->id === $post->user_id;
    }

    /**
     * Determine si l'utilisateur peut créer un post
     */
    public function create(User $user): bool
    {
        return !$user->is_banned;
    }

    /**
     * Determine si l'utilisateur peut modifier un post
     */
    public function update(User $user, Post $post): bool
    {
        // L'auteur ou un admin peut modifier
        return $user->id === $post->user_id || $user->isAdmin();
    }

    /**
     * Determine si l'utilisateur peut supprimer un post
     */
    public function delete(User $user, Post $post): bool
    {
        // L'auteur ou un admin peut supprimer
        return $user->id === $post->user_id || $user->isAdmin();
    }

    /**
     * Determine si l'utilisateur peut restaurer un post
     */
    public function restore(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->isAdmin();
    }

    /**
     * Determine si l'utilisateur peut supprimer définitivement un post
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return $user->isAdmin();
    }
}
