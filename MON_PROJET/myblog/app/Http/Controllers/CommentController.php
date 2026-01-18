<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    // GET /api/posts/{id}/comments - Lister les commentaires d'un article
    public function index($postId)
    {
        $post = Post::findOrFail($postId);

        $comments = $post->comments()
            ->with('user:id,name') // Charger uniquement id et name de l'utilisateur
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $comments
        ], Response::HTTP_OK);
    }

    // POST /api/posts/{id}/comments - Créer un commentaire
    public function store(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);

        $validated = $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'post_id' => $postId,
            'content' => $validated['content']
        ]);

        // Charger la relation user pour la réponse
        $comment->load('user:id,name');

        return response()->json([
            'success' => true,
            'message' => 'Commentaire ajouté avec succès',
            'data' => $comment
        ], Response::HTTP_CREATED);
    }

    // PUT /api/comments/{id} - Modifier un commentaire
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        // Vérifier que l'utilisateur est bien l'auteur du commentaire
        if ($comment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à modifier ce commentaire'
            ], Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $comment->update($validated);
        $comment->load('user:id,name');

        return response()->json([
            'success' => true,
            'message' => 'Commentaire modifié avec succès',
            'data' => $comment
        ], Response::HTTP_OK);
    }

    // DELETE /api/comments/{id} - Supprimer un commentaire
    public function destroy(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        // Vérifier que l'utilisateur est bien l'auteur
        if ($comment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à supprimer ce commentaire'
            ], Response::HTTP_FORBIDDEN);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Commentaire supprimé avec succès'
        ], Response::HTTP_OK);
    }
}
