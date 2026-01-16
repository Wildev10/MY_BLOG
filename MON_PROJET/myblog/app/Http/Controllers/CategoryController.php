<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    // GET /api/categories - Lister toutes les catégories
    public function index()
    {
        $categories = Category::withCount('posts')->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ], Response::HTTP_OK);
    }

    // POST /api/categories - Créer une catégorie (admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'color' => 'nullable|string|max:7'
        ]);

        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Catégorie créée avec succès',
            'data' => $category
        ], Response::HTTP_CREATED);
    }

    // GET /api/categories/{slug}/posts - Posts d'une catégorie
    public function posts($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $posts = $category->posts()->with('user', 'category')->paginate(10);

        return response()->json([
            'success' => true,
            'category' => $category,
            'data' => $posts
        ], Response::HTTP_OK);
    }
}
