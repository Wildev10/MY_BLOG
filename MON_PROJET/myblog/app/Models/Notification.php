<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_user_id',
        'type',
        'post_id',
        'comment_id',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean'
    ];

    // Relation : Utilisateur qui reçoit la notification
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation : Utilisateur qui a déclenché la notification
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    // Relation : Article concerné
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Relation : Commentaire concerné (si type = comment)
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
