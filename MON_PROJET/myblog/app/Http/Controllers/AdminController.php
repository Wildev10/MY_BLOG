<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // GET /api/admin/stats - Statistiques globales
    public function stats()
    {
        $stats = [
            'users' => [
                'total' => User::count(),
                'admins' => User::where('role', 'admin')->count(),
                'banned' => User::where('is_banned', true)->count(),
                'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
            ],
            'posts' => [
                'total' => Post::count(),
                'published_today' => Post::whereDate('created_at', today())->count(),
                'with_images' => Post::whereNotNull('image')->count(),
            ],
            'comments' => [
                'total' => Comment::count(),
                'today' => Comment::whereDate('created_at', today())->count(),
            ],
            'likes' => [
                'total' => Like::count(),
                'today' => Like::whereDate('created_at', today())->count(),
            ],
            'categories' => [
                'total' => Category::count(),
            ],
        ];

        // Posts par jour (7 derniers jours)
        $postsPerDay = Post::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Users par jour (7 derniers jours)
        $usersPerDay = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Articles récents (5 derniers)
        $recentPosts = Post::with(['user:id,name', 'category:id,name,color'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->take(5)
            ->get(['id', 'title', 'user_id', 'category_id', 'created_at']);

        // Articles populaires (par likes)
        $popularPosts = Post::with(['user:id,name', 'category:id,name,color'])
            ->withCount(['likes', 'comments'])
            ->orderByDesc('likes_count')
            ->take(5)
            ->get(['id', 'title', 'user_id', 'category_id', 'created_at']);

        // Articles les plus anciens
        $oldestPosts = Post::with(['user:id,name', 'category:id,name,color'])
            ->withCount(['likes', 'comments'])
            ->oldest()
            ->take(5)
            ->get(['id', 'title', 'user_id', 'category_id', 'created_at']);

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'charts' => [
                    'posts_per_day' => $postsPerDay,
                    'users_per_day' => $usersPerDay,
                ],
                'posts' => [
                    'recent' => $recentPosts,
                    'popular' => $popularPosts,
                    'oldest' => $oldestPosts,
                ]
            ]
        ], Response::HTTP_OK);
    }

    // GET /api/admin/users - Liste de tous les utilisateurs
    public function users(Request $request)
    {
        $query = User::withCount(['posts', 'comments', 'likes']);

        // Filtres
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('is_banned')) {
            $query->where('is_banned', $request->is_banned);
        }

        // Recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $users
        ], Response::HTTP_OK);
    }

    // PUT /api/admin/users/{id}/toggle-admin - Promouvoir/Rétrograder admin
    public function toggleAdmin(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Empêcher de se rétrograder soi-même
        if ($user->id === $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas modifier votre propre rôle'
            ], Response::HTTP_FORBIDDEN);
        }

        $user->role = $user->role === 'admin' ? 'user' : 'admin';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => $user->role === 'admin' ? 'Utilisateur promu admin' : 'Admin rétrogradé utilisateur',
            'data' => $user
        ], Response::HTTP_OK);
    }

    // PUT /api/admin/users/{id}/toggle-ban - Bannir/Débannir
    public function toggleBan(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Empêcher de se bannir soi-même
        if ($user->id === $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas vous bannir vous-même'
            ], Response::HTTP_FORBIDDEN);
        }

        // Empêcher de bannir un autre admin
        if ($user->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas bannir un administrateur'
            ], Response::HTTP_FORBIDDEN);
        }

        $user->is_banned = !$user->is_banned;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => $user->is_banned ? 'Utilisateur banni' : 'Utilisateur débanni',
            'data' => $user
        ], Response::HTTP_OK);
    }

    // GET /api/admin/posts - Liste de tous les articles
    public function posts(Request $request)
    {
        $query = Post::with(['user:id,name,email', 'category'])
            ->withCount(['likes', 'comments']);

        // Filtres
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest()->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $posts
        ], Response::HTTP_OK);
    }

    // DELETE /api/admin/posts/{id} - Supprimer n'importe quel article
    public function deletePost($id)
    {
        $post = Post::findOrFail($id);

        // Supprimer l'image si elle existe
        if ($post->image) {
            \Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article supprimé avec succès'
        ], Response::HTTP_OK);
    }

    // GET /api/admin/comments - Liste de tous les commentaires
    public function comments(Request $request)
    {
        $query = Comment::with(['user:id,name', 'post:id,title']);

        // Filtres
        if ($request->has('post_id')) {
            $query->where('post_id', $request->post_id);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('content', 'like', "%{$search}%");
        }

        $comments = $query->latest()->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $comments
        ], Response::HTTP_OK);
    }

    // DELETE /api/admin/comments/{id} - Supprimer n'importe quel commentaire
    public function deleteComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Commentaire supprimé avec succès'
        ], Response::HTTP_OK);
    }

    // GET /api/admin/categories - Gestion des catégories (avec stats)
    public function categoriesStats()
    {
        $categories = Category::withCount('posts')->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ], Response::HTTP_OK);
    }

    // PUT /api/admin/categories/{id} - Modifier une catégorie
    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:categories,name,' . $id,
            'color' => 'sometimes|string|max:7'
        ]);

        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Catégorie modifiée avec succès',
            'data' => $category
        ], Response::HTTP_OK);
    }

    // DELETE /api/admin/categories/{id} - Supprimer une catégorie
    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);

        // Vérifier s'il y a des posts liés
        if ($category->posts()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer une catégorie contenant des articles'
            ], Response::HTTP_BAD_REQUEST);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catégorie supprimée avec succès'
        ], Response::HTTP_OK);
    }
}
