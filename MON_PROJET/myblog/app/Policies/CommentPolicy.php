<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Determine si l'utilisateur peut voir tous les commentaires
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine si l'utilisateur peut voir un commentaire
     */
    public function view(?User $user, Comment $comment): bool
    {
        return true;
    }

    /**
     * Determine si l'utilisateur peut créer un commentaire
     */
    public function create(User $user): bool
    {
        return !$user->is_banned;
    }

    /**
     * Determine si l'utilisateur peut modifier un commentaire
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    /**
     * Determine si l'utilisateur peut supprimer un commentaire
     */
    public function delete(User $user, Comment $comment): bool
    {
        // L'auteur du commentaire, l'auteur du post, ou un admin
        return $user->id === $comment->user_id
            || $user->id === $comment->post->user_id
            || $user->isAdmin();
    }
}
