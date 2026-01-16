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
}
