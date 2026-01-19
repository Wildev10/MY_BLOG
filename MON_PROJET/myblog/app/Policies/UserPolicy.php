<?php

namespace App\Policies;

use App\Models\User;

/**
 * Policy pour les utilisateurs
 *
 * Définit les règles d'autorisation pour les actions sur les profils
 */
class UserPolicy
{
    /**
     * Déterminer si l'utilisateur peut voir le profil complet
     */
    public function view(User $currentUser, User $targetUser): bool
    {
        // Tout le monde peut voir les profils publics
        return true;
    }

    /**
     * Déterminer si l'utilisateur peut modifier le profil
     */
    public function update(User $currentUser, User $targetUser): bool
    {
        // Seul le propriétaire peut modifier son profil
        return $currentUser->id === $targetUser->id;
    }

    /**
     * Déterminer si l'utilisateur peut supprimer le compte
     */
    public function delete(User $currentUser, User $targetUser): bool
    {
        // Seul le propriétaire peut supprimer son compte
        return $currentUser->id === $targetUser->id;
    }
}
