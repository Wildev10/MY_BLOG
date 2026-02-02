<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FollowController extends Controller
{
    /**
     * POST /api/users/{username}/follow - Follow/Unfollow un utilisateur
     */
    public function toggle(Request $request, string $username)
    {
        $userToFollow = User::where('username', $username)->firstOrFail();
        $currentUser = $request->user();

        // On ne peut pas se suivre soi-même
        if ($currentUser->id === $userToFollow->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas vous suivre vous-même'
            ], Response::HTTP_BAD_REQUEST);
        }

        $existingFollow = Follow::where('follower_id', $currentUser->id)
            ->where('following_id', $userToFollow->id)
            ->first();

        if ($existingFollow) {
            // Unfollow
            $existingFollow->delete();

            // Supprimer la notification
            Notification::where('user_id', $userToFollow->id)
                ->where('from_user_id', $currentUser->id)
                ->where('type', 'follow')
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Vous ne suivez plus ' . $userToFollow->name,
                'following' => false,
                'followers_count' => $userToFollow->followers()->count()
            ], Response::HTTP_OK);
        } else {
            // Follow
            Follow::create([
                'follower_id' => $currentUser->id,
                'following_id' => $userToFollow->id
            ]);

            // Créer une notification
            Notification::create([
                'user_id' => $userToFollow->id,
                'from_user_id' => $currentUser->id,
                'type' => 'follow'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vous suivez maintenant ' . $userToFollow->name,
                'following' => true,
                'followers_count' => $userToFollow->followers()->count()
            ], Response::HTTP_CREATED);
        }
    }

    /**
     * GET /api/users/{username}/followers - Liste des followers
     */
    public function followers(string $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        $followers = $user->followers()
            ->select('users.id', 'users.name', 'users.username', 'users.avatar', 'users.bio')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $followers
        ], Response::HTTP_OK);
    }

    /**
     * GET /api/users/{username}/following - Liste des personnes suivies
     */
    public function following(string $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        $following = $user->following()
            ->select('users.id', 'users.name', 'users.username', 'users.avatar', 'users.bio')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $following
        ], Response::HTTP_OK);
    }

    /**
     * GET /api/users/{username}/follow/status - Vérifier si on suit
     */
    public function status(Request $request, string $username)
    {
        $userToCheck = User::where('username', $username)->firstOrFail();

        $isFollowing = Follow::where('follower_id', $request->user()->id)
            ->where('following_id', $userToCheck->id)
            ->exists();

        return response()->json([
            'success' => true,
            'following' => $isFollowing
        ], Response::HTTP_OK);
    }

    /**
     * GET /api/feed - Feed des posts des personnes suivies
     */
    public function feed(Request $request)
    {
        $followingIds = $request->user()
            ->following()
            ->pluck('users.id');

        $posts = \App\Models\Post::whereIn('user_id', $followingIds)
            ->where('status', 'published')
            ->with(['user:id,name,username,avatar', 'category:id,name,slug'])
            ->withCount('likes')
            ->latest()
            ->paginate(10);

        // Ajouter si l'utilisateur a liké
        $userId = $request->user()->id;
        $posts->getCollection()->transform(function ($post) use ($userId) {
            $post->is_liked = $post->isLikedBy($userId);
            $post->is_bookmarked = $post->isBookmarkedBy($userId);
            return $post;
        });

        return response()->json([
            'success' => true,
            'data' => $posts
        ], Response::HTTP_OK);
    }
}
