<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostView;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    // GET /api/posts - Lister TOUS les articles publiés (avec pagination)
    public function index(Request $request)
    {
        $posts = Post::with(['user:id,name,username,avatar', 'category:id,name,slug', 'tags:id,name,slug,color'])
            ->withCount(['likes', 'comments'])
            ->published() // Uniquement les posts publiés
            ->latest()
            ->paginate(10);

        // Ajouter si l'utilisateur connecté a liké/bookmarké chaque post
        if ($request->user()) {
            $userId = $request->user()->id;
            $posts->getCollection()->transform(function ($post) use ($userId) {
                $post->is_liked = $post->isLikedBy($userId);
                $post->is_bookmarked = $post->isBookmarkedBy($userId);
                return $post;
            });
        }

        return response()->json([
            'success' => true,
            'data' => $posts
        ], Response::HTTP_OK);
    }

    // GET /api/posts/popular - Articles les plus populaires
    public function popular(Request $request)
    {
        $posts = Cache::remember('popular_posts', 3600, function () {
            return Post::with(['user:id,name,username,avatar', 'category:id,name,slug'])
                ->withCount(['likes', 'comments'])
                ->published()
                ->orderByDesc('views_count')
                ->orderByDesc('likes_count')
                ->take(10)
                ->get();
        });

        return response()->json([
            'success' => true,
            'data' => $posts
        ], Response::HTTP_OK);
    }

    // POST /api/posts - Créer un article
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        // Gérer l'upload de l'image
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $validated['image'] = $path;
        }

        // Statut par défaut : published
        $validated['status'] = $request->input('status', 'published');

        // Création de l'article
        $post = $request->user()->posts()->create($validated);

        // Synchroniser les tags si présents
        if ($request->has('tags') && is_array($request->tags)) {
            $post->syncTags($request->tags);
        }

        // Charger les relations
        $post->load(['user', 'category', 'tags']);

        // Invalider le cache des posts populaires
        Cache::forget('popular_posts');

        return response()->json([
            'success' => true,
            'message' => $validated['status'] === 'draft'
                ? 'Brouillon enregistré avec succès'
                : 'Article publié avec succès',
            'data' => $post
        ], Response::HTTP_CREATED);
    }

    // GET /api/posts/{id} - Voir UN article spécifique
    public function show(Request $request, $id)
    {
        $post = Post::with(['user:id,name,username,avatar,bio', 'category:id,name,slug', 'tags:id,name,slug,color'])
            ->withCount(['likes', 'comments'])
            ->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Article non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        // Vérifier si le post est un brouillon (seul l'auteur peut le voir)
        if ($post->status === 'draft') {
            if (!$request->user() || $request->user()->id !== $post->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Article non trouvé'
                ], Response::HTTP_NOT_FOUND);
            }
        }

        // Enregistrer la vue
        PostView::recordView(
            $post,
            $request->user()?->id,
            $request->ip(),
            $request->userAgent()
        );

        // Vérifier si l'utilisateur connecté a liké/bookmarké
        if ($request->user()) {
            $post->is_liked = $post->isLikedBy($request->user()->id);
            $post->is_bookmarked = $post->isBookmarkedBy($request->user()->id);
        } else {
            $post->is_liked = false;
            $post->is_bookmarked = false;
        }

        return response()->json([
            'success' => true,
            'data' => $post
        ], Response::HTTP_OK);
    }

    // PUT/PATCH /api/posts/{id} - Modifier un article
    public function update(UpdatePostRequest $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Article non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        // Vérification via Policy
        $this->authorize('update', $post);

        $validated = $request->validated();

        // Gérer l'upload de la nouvelle image
        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $path = $request->file('image')->store('posts', 'public');
            $validated['image'] = $path;
        }

        // Gérer la suppression d'image
        if ($request->input('remove_image') === '1') {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $validated['image'] = null;
        }

        $post->update($validated);

        // Synchroniser les tags si présents
        if ($request->has('tags') && is_array($request->tags)) {
            $post->syncTags($request->tags);
        }

        $post->load(['user', 'category', 'tags']);

        // Invalider le cache
        Cache::forget('popular_posts');

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

        // Vérification via Policy
        $this->authorize('delete', $post);

        // Supprimer l'image si elle existe
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        // Invalider le cache
        Cache::forget('popular_posts');

        return response()->json([
            'success' => true,
            'message' => 'Article supprimé avec succès'
        ], Response::HTTP_OK);
    }

    // GET /api/my-posts - Voir MES propres articles publiés
    public function myPosts(Request $request)
    {
        $posts = $request->user()
            ->posts()
            ->with(['category:id,name,slug', 'tags:id,name,slug'])
            ->withCount(['likes', 'comments'])
            ->published()
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $posts
        ], Response::HTTP_OK);
    }

    // GET /api/my-drafts - Voir MES brouillons
    public function myDrafts(Request $request)
    {
        $posts = $request->user()
            ->posts()
            ->with(['category:id,name,slug', 'tags:id,name,slug'])
            ->draft()
            ->latest()
            ->paginate(10);

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

        $posts = Post::with(['user:id,name,username,avatar', 'category:id,name,slug', 'tags:id,name,slug'])
            ->withCount(['likes', 'comments'])
            ->published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhere('content', 'like', '%' . $query . '%');
            })
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'query' => $query,
            'data' => $posts
        ], Response::HTTP_OK);
    }
}
