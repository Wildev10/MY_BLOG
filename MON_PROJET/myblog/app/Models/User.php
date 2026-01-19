<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Modèle User avec support de profil complet
 *
 * Fonctionnalités:
 * - Profil avec avatar, bio, location
 * - Username unique pour URL publique
 * - Soft delete pour suppression de compte
 * - Statistiques (posts, commentaires)
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * Attributs assignables en masse
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'bio',
        'location',
    ];

    /**
     * Attributs cachés lors de la sérialisation
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attributs ajoutés automatiquement à la sérialisation
     */
    protected $appends = [
        'avatar_url',
        'posts_count',
        'comments_count',
        'last_post_date',
    ];

    /**
     * Cast des attributs
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'deleted_at' => 'datetime',
        ];
    }

    // ==================== ACCESSORS ====================

    /**
     * URL complète de l'avatar
     * Retourne un placeholder si aucun avatar
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Placeholder avec initiales via UI Avatars
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&size=200&background=6366f1&color=ffffff&bold=true";
    }

    /**
     * Nombre de posts publiés
     */
    public function getPostsCountAttribute(): int
    {
        return $this->posts()->count();
    }

    /**
     * Nombre de commentaires
     */
    public function getCommentsCountAttribute(): int
    {
        return $this->comments()->count();
    }

    /**
     * Date du dernier post
     */
    public function getLastPostDateAttribute(): ?string
    {
        $lastPost = $this->posts()->latest()->first();
        return $lastPost?->created_at?->toISOString();
    }

    // ==================== RELATIONS ====================

    /**
     * Un utilisateur a plusieurs posts
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Un utilisateur a plusieurs likes
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Un utilisateur a plusieurs commentaires
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Relation : Un utilisateur a plusieurs notifications
    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    // Notifications non lues
    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', false);
    }

    // ==================== MÉTHODES UTILITAIRES ====================

    /**
     * Générer un username unique à partir du nom
     */
    public static function generateUsername(string $name): string
    {
        $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name));
        $username = $baseUsername;
        $counter = 1;

        while (self::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Trouver par username ou échouer
     */
    public static function findByUsernameOrFail(string $username): self
    {
        return self::where('username', $username)->firstOrFail();
    }
}
