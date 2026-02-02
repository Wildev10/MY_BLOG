<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Post $post;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->post = Post::factory()->create(['status' => 'published']);
    }

    /**
     * Test de like d'un post
     */
    public function test_user_can_like_post(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson("/api/posts/{$this->post->id}/like");

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'liked' => true
            ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);
    }

    /**
     * Test de unlike d'un post
     */
    public function test_user_can_unlike_post(): void
    {
        // D'abord, liker le post
        Like::create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/posts/{$this->post->id}/like");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'liked' => false
            ]);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);
    }

    /**
     * Test qu'un utilisateur non authentifié ne peut pas liker
     */
    public function test_unauthenticated_user_cannot_like(): void
    {
        $response = $this->postJson("/api/posts/{$this->post->id}/like");

        $response->assertStatus(401);
    }

    /**
     * Test de récupération des likes d'un post
     */
    public function test_can_get_post_likes(): void
    {
        Like::create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/posts/{$this->post->id}/likes");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'likes_count',
                'data'
            ]);
    }
}
