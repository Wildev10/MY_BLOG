<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LikeController extends Controller
{
    // POST /api/posts/{id}/like - Liker/Unliker un article
    public function toggle(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $userId = $request->user()->id;

        // Vérifier si l'utilisateur a déjà liké
        $existingLike = Like::where('user_id', $userId)
                            ->where('post_id', $postId)
                            ->first();

        if ($existingLike) {
            // Déjà liké → On unlike (supprime le like)
            $existingLike->delete();

            return response()->json([
                'success' => true,
                'message' => 'Like retiré',
                'liked' => false,
                'likes_count' => $post->likes()->count()
            ], Response::HTTP_OK);
        } else {
            // Pas encore liké → On like
            Like::create([
                'user_id' => $userId,
                'post_id' => $postId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Article liké',
                'liked' => true,
                'likes_count' => $post->likes()->count()
            ], Response::HTTP_CREATED);
        }
    }

    // GET /api/posts/{id}/likes - Obtenir les likes d'un article
    public function getLikes($postId)
    {
        $post = Post::findOrFail($postId);

        $likes = $post->likes()->with('user:id,name')->get();

        return response()->json([
            'success' => true,
            'likes_count' => $likes->count(),
            'data' => $likes
        ], Response::HTTP_OK);
    }
}
