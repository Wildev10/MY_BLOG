<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TagController extends Controller
{
    /**
     * GET /api/tags - Lister tous les tags
     */
    public function index()
    {
        $tags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tags
        ], Response::HTTP_OK);
    }

    /**
     * GET /api/tags/popular - Tags les plus populaires
     */
    public function popular()
    {
        $tags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tags
        ], Response::HTTP_OK);
    }

    /**
     * GET /api/tags/{slug}/posts - Posts d'un tag
     */
    public function posts(string $slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = $tag->posts()
            ->with(['user:id,name,avatar', 'category:id,name,slug'])
            ->withCount('likes')
            ->where('status', 'published')
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'tag' => $tag,
            'data' => $posts
        ], Response::HTTP_OK);
    }

    /**
     * POST /api/tags - Créer un tag (admin)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:tags,name',
            'color' => 'nullable|string|max:7'
        ]);

        $tag = Tag::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tag créé avec succès',
            'data' => $tag
        ], Response::HTTP_CREATED);
    }

    /**
     * DELETE /api/tags/{id} - Supprimer un tag (admin)
     */
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tag supprimé avec succès'
        ], Response::HTTP_OK);
    }
}
