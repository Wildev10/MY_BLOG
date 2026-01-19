<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour ajouter les champs de profil à la table users
 *
 * Champs ajoutés:
 * - username: pseudo unique pour l'URL publique
 * - avatar: chemin vers la photo de profil
 * - bio: biographie courte
 * - location: localisation (optionnelle)
 * - deleted_at: pour le soft delete
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Username unique pour le profil public (/users/{username})
            $table->string('username')->unique()->nullable()->after('name');

            // Photo de profil (chemin du fichier)
            $table->string('avatar')->nullable()->after('email');

            // Biographie courte (max 500 caractères)
            $table->text('bio')->nullable()->after('avatar');

            // Localisation (ville, pays)
            $table->string('location')->nullable()->after('bio');

            // Soft delete pour suppression de compte
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'avatar', 'bio', 'location']);
            $table->dropSoftDeletes();
        });
    }
};
