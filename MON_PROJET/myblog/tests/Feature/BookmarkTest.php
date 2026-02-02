<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Bookmark;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookmarkTest extends TestCase
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
     * Test de bookmark d'un post
     */
    public function test_user_can_bookmark_post(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson("/api/posts/{$this->post->id}/bookmark");

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'bookmarked' => true
            ]);

        $this->assertDatabaseHas('bookmarks', [
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);
    }

    /**
     * Test de suppression de bookmark
     */
    public function test_user_can_remove_bookmark(): void
    {
        Bookmark::create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/posts/{$this->post->id}/bookmark");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'bookmarked' => false
            ]);

        $this->assertDatabaseMissing('bookmarks', [
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);
    }

    /**
     * Test de listing des bookmarks
     */
    public function test_user_can_list_bookmarks(): void
    {
        $posts = Post::factory()->count(3)->create(['status' => 'published']);

        foreach ($posts as $post) {
            Bookmark::create([
                'user_id' => $this->user->id,
                'post_id' => $post->id
            ]);
        }

        $response = $this->actingAs($this->user)
            ->getJson('/api/bookmarks');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data.data');
    }

    /**
     * Test de statut de bookmark
     */
    public function test_can_check_bookmark_status(): void
    {
        Bookmark::create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/posts/{$this->post->id}/bookmark/status");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'bookmarked' => true
            ]);
    }
}
