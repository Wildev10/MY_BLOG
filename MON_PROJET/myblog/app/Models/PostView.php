<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostView extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'user_id', 'ip_address', 'user_agent'];

    /**
     * Relation : Une vue appartient à un post
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Relation : Une vue peut appartenir à un utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Enregistrer une vue unique (évite les doublons)
     */
    public static function recordView(Post $post, ?int $userId, ?string $ip, ?string $userAgent): bool
    {
        // Vérifier si cette vue existe déjà dans les dernières 24h
        $existingView = self::where('post_id', $post->id)
            ->where(function ($query) use ($userId, $ip) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('ip_address', $ip);
                }
            })
            ->where('created_at', '>', now()->subDay())
            ->exists();

        if (!$existingView) {
            self::create([
                'post_id' => $post->id,
                'user_id' => $userId,
                'ip_address' => $ip,
                'user_agent' => $userAgent
            ]);

            // Mettre à jour le compteur de vues du post
            $post->increment('views_count');

            return true;
        }

        return false;
    }
}
