<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{
    // GET /api/posts - Lister TOUS les articles (avec pagination)
    public function index()
    {
        // On récupère 10 articles par page, avec les infos de l'auteur et de la catégorie
        $posts = Post::with(['user', 'category'])->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $posts
        ], Response::HTTP_OK);
    }

    // POST /api/posts - Créer un article
    public function store(Request $request)
    {
        // Validation : On vérifie que les données sont correctes
        $validated = $request->validate([
            'title' => 'required|string|max:255', // Titre obligatoire
            'content' => 'required|string',       // Contenu obligatoire
            'category_id' => 'nullable|exists:categories,id' // Catégorie optionnelle
        ]);

        // Création automatique : L'article est lié à l'utilisateur connecté
        $post = $request->user()->posts()->create($validated);

        // On charge les relations 'user' et 'category' pour l'afficher dans la réponse
        $post->load(['user', 'category']);

        return response()->json([
            'success' => true,
            'message' => 'Article créé avec succès',
            'data' => $post
        ], Response::HTTP_CREATED);
    }

    // GET /api/posts/{id} - Voir UN article spécifique
    public function show($id)
    {
        // On cherche l'article avec son auteur et sa catégorie
        $post = Post::with(['user', 'category'])->find($id);

        // Si l'article n'existe pas, on retourne une erreur 404
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Article non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => $post
        ], Response::HTTP_OK);
    }

    // PUT/PATCH /api/posts/{id} - Modifier un article
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Article non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        // VÉRIFICATION IMPORTANTE : Seul l'auteur peut modifier son article
        if ($post->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à modifier cet article'
            ], Response::HTTP_FORBIDDEN); // 403 Forbidden
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        $post->update($validated);
        $post->load('user' , 'category');

        return response()->json([
            'success' => true,
            'message' => 'Article mis à jour avec succès',
            'data' => $post
        ], Response::HTTP_OK);
    }

    // DELETE /api/posts/{id} - Supprimer un article
    public function destroy(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Article non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        // VÉRIFICATION : Seul l'auteur peut supprimer son article
        if ($post->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à supprimer cet article'
            ], Response::HTTP_FORBIDDEN);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article supprimé avec succès'
        ], Response::HTTP_OK);
    }

    // GET /api/my-posts - Voir MES propres articles
    public function myPosts(Request $request)
    {
        // On récupère uniquement les articles de l'utilisateur connecté avec leur catégorie
        $posts = $request->user()->posts()->with('category')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $posts
        ], Response::HTTP_OK);
    }

    // GET /api/posts/search?q=laravel - Rechercher des articles
public function search(Request $request)
{
    $query = $request->input('q', '');

    if (empty($query)) {
        return response()->json([
            'success' => false,
            'message' => 'Veuillez fournir un terme de recherche'
        ], Response::HTTP_BAD_REQUEST);
    }

    $posts = Post::with('user', 'category')
        ->where(function ($q) use ($query) {
            $q->where('title', 'like', '%' . $query . '%')
              ->orWhere('content', 'like', '%' . $query . '%');
        })
        ->paginate(10);

    return response()->json([
        'success' => true,
        'query' => $query,
        'data' => $posts
    ], Response::HTTP_OK);
}

}
