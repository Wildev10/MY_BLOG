<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Notification;
use App\Http\Requests\StoreCommentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    // GET /api/posts/{id}/comments - Lister les commentaires d'un article
    public function index($postId)
    {
        $post = Post::findOrFail($postId);

        $comments = $post->comments()
            ->with('user:id,name,username,avatar')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $comments
        ], Response::HTTP_OK);
    }

    // POST /api/posts/{id}/comments - Créer un commentaire
    public function store(StoreCommentRequest $request, $postId)
    {
        $post = Post::findOrFail($postId);

        $validated = $request->validated();

        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'post_id' => $postId,
            'content' => $validated['content']
        ]);

        $comment->load('user:id,name,username,avatar');

        // Créer une notification (sauf si c'est son propre article)
        if ($post->user_id !== $request->user()->id) {
            Notification::create([
                'user_id' => $post->user_id,
                'from_user_id' => $request->user()->id,
                'type' => 'comment',
                'post_id' => $postId,
                'comment_id' => $comment->id
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Commentaire ajouté avec succès',
            'data' => $comment
        ], Response::HTTP_CREATED);
    }

    // PUT /api/comments/{id} - Modifier un commentaire
    public function update(StoreCommentRequest $request, $id)
    {
        $comment = Comment::findOrFail($id);

        // Vérification via Policy
        $this->authorize('update', $comment);

        $comment->update($request->validated());
        $comment->load('user:id,name,username,avatar');

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

        // Vérification via Policy (auteur du commentaire, auteur du post, ou admin)
        $this->authorize('delete', $comment);

        // Supprimer la notification associée
        Notification::where('comment_id', $comment->id)->delete();

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Commentaire supprimé avec succès'
        ], Response::HTTP_OK);
    }
}
