<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookmarkController extends Controller
{
    /**
     * GET /api/bookmarks - Lister mes bookmarks
     */
    public function index(Request $request)
    {
        $bookmarks = $request->user()
            ->bookmarks()
            ->with(['post' => function ($query) {
                $query->with(['user:id,name,avatar', 'category:id,name,slug'])
                    ->withCount('likes');
            }])
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $bookmarks
        ], Response::HTTP_OK);
    }

    /**
     * POST /api/posts/{id}/bookmark - Toggle bookmark
     */
    public function toggle(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $userId = $request->user()->id;

        $existingBookmark = Bookmark::where('user_id', $userId)
            ->where('post_id', $postId)
            ->first();

        if ($existingBookmark) {
            $existingBookmark->delete();

            return response()->json([
                'success' => true,
                'message' => 'Article retiré des favoris',
                'bookmarked' => false
            ], Response::HTTP_OK);
        } else {
            Bookmark::create([
                'user_id' => $userId,
                'post_id' => $postId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Article ajouté aux favoris',
                'bookmarked' => true
            ], Response::HTTP_CREATED);
        }
    }

    /**
     * GET /api/posts/{id}/bookmark/status - Vérifier si bookmarké
     */
    public function status(Request $request, $postId)
    {
        $isBookmarked = Bookmark::where('user_id', $request->user()->id)
            ->where('post_id', $postId)
            ->exists();

        return response()->json([
            'success' => true,
            'bookmarked' => $isBookmarked
        ], Response::HTTP_OK);
    }
}
