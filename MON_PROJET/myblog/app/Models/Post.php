<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'category_id',
        'image',
        'status',
        'reading_time',
        'views_count'
    ];

    protected $appends = ['image_url', 'reading_time_text'];

    /**
     * Scope : Uniquement les posts publiés
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope : Uniquement les brouillons
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Boot du model - Calcul automatique du temps de lecture
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($post) {
            // Calculer le temps de lecture (environ 200 mots par minute)
            $wordCount = str_word_count(strip_tags($post->content));
            $post->reading_time = max(1, ceil($wordCount / 200));
        });
    }

    // Relation : Un post appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation : Un post appartient à une catégorie
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

       // NOUVEAU : Relation : Un post a plusieurs likes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // NOUVEAU : Vérifier si un utilisateur a liké ce post
    public function isLikedBy($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    // NOUVEAU : Compter le nombre de likes
    public function likesCount()
    {
        return $this->likes()->count();
    }

    // NOUVEAU : Relation : Un post a plusieurs commentaires
    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    // Relation : Un post a plusieurs tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag')->withTimestamps();
    }

    // Relation : Un post a plusieurs bookmarks
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    // Relation : Un post a plusieurs vues
    public function views()
    {
        return $this->hasMany(PostView::class);
    }

    // Vérifier si un utilisateur a bookmarké ce post
    public function isBookmarkedBy($userId)
    {
        return $this->bookmarks()->where('user_id', $userId)->exists();
    }

    // Accessor pour l'URL complète de l'image
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        return asset('storage/' . $this->image);
    }

    // Accessor pour le texte du temps de lecture
    public function getReadingTimeTextAttribute(): string
    {
        $minutes = $this->reading_time ?? 1;
        return $minutes . ' min de lecture';
    }

    // Synchroniser les tags avec le post
    public function syncTags(array $tagNames): void
    {
        $tagIds = collect($tagNames)->map(function ($name) {
            return Tag::findOrCreateByName($name)->id;
        });

        $this->tags()->sync($tagIds);
    }
}
