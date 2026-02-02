<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
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
     * Test de listing des commentaires
     */
    public function test_can_list_comments(): void
    {
        Comment::factory()->count(3)->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);

        $response = $this->getJson("/api/posts/{$this->post->id}/comments");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data' => [
                        '*' => ['id', 'content', 'user']
                    ]
                ]
            ]);
    }

    /**
     * Test de création d'un commentaire
     */
    public function test_authenticated_user_can_create_comment(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson("/api/posts/{$this->post->id}/comments", [
                'content' => 'Super article !'
            ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('comments', [
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'content' => 'Super article !'
        ]);
    }

    /**
     * Test qu'un utilisateur non authentifié ne peut pas commenter
     */
    public function test_unauthenticated_user_cannot_comment(): void
    {
        $response = $this->postJson("/api/posts/{$this->post->id}/comments", [
            'content' => 'Test'
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test de modification d'un commentaire
     */
    public function test_author_can_update_own_comment(): void
    {
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);

        $response = $this->actingAs($this->user)
            ->putJson("/api/comments/{$comment->id}", [
                'content' => 'Commentaire modifié'
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Commentaire modifié'
        ]);
    }

    /**
     * Test qu'un autre utilisateur ne peut pas modifier le commentaire
     */
    public function test_other_user_cannot_update_comment(): void
    {
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);

        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)
            ->putJson("/api/comments/{$comment->id}", [
                'content' => 'Tentative de modification'
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test de suppression d'un commentaire
     */
    public function test_author_can_delete_own_comment(): void
    {
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id
        ]);
    }

    /**
     * Test de validation du contenu du commentaire
     */
    public function test_comment_content_is_required(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson("/api/posts/{$this->post->id}/comments", [
                'content' => ''
            ]);

        $response->assertStatus(422);
    }
}
