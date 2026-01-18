<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'user_id', 'category_id']; // ← Ajoutez category_id

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
}
