<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->category = Category::create([
            'name' => 'Tech',
            'slug' => 'tech',
            'color' => '#3B82F6'
        ]);
    }

    /**
     * Test de listing des posts
     */
    public function test_can_list_published_posts(): void
    {
        Post::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'status' => 'published'
        ]);

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data' => [
                        '*' => ['id', 'title', 'content', 'user']
                    ]
                ]
            ]);
    }

    /**
     * Test de création d'un post
     */
    public function test_authenticated_user_can_create_post(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/posts', [
                'title' => 'Mon premier article de test',
                'content' => 'Ceci est le contenu de mon premier article de test. Il doit faire au moins 50 caractères.',
                'category_id' => $this->category->id,
            ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Mon premier article de test',
            'user_id' => $this->user->id
        ]);
    }

    /**
     * Test de création d'un brouillon
     */
    public function test_user_can_create_draft_post(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/posts', [
                'title' => 'Mon brouillon de test',
                'content' => 'Ceci est le contenu de mon brouillon de test. Il doit faire au moins 50 caractères.',
                'status' => 'draft',
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('posts', [
            'title' => 'Mon brouillon de test',
            'status' => 'draft'
        ]);
    }

    /**
     * Test qu'un utilisateur non authentifié ne peut pas créer de post
     */
    public function test_unauthenticated_user_cannot_create_post(): void
    {
        $response = $this->postJson('/api/posts', [
            'title' => 'Test',
            'content' => 'Contenu de test qui doit faire au moins 50 caractères pour passer la validation.',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test de vue d'un post spécifique
     */
    public function test_can_view_single_published_post(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'published'
        ]);

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $post->id,
                    'title' => $post->title
                ]
            ]);
    }

    /**
     * Test qu'un brouillon n'est pas visible publiquement
     */
    public function test_draft_post_is_not_publicly_visible(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(404);
    }

    /**
     * Test que l'auteur peut voir son brouillon
     */
    public function test_author_can_view_own_draft(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200);
    }

    /**
     * Test de modification d'un post
     */
    public function test_author_can_update_own_post(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
            ->putJson("/api/posts/{$post->id}", [
                'title' => 'Titre modifié pour le test'
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Titre modifié pour le test'
        ]);
    }

    /**
     * Test qu'un autre utilisateur ne peut pas modifier le post
     */
    public function test_other_user_cannot_update_post(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)
            ->putJson("/api/posts/{$post->id}", [
                'title' => 'Tentative de modification'
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test de suppression d'un post
     */
    public function test_author_can_delete_own_post(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id
        ]);
    }

    /**
     * Test de recherche
     */
    public function test_can_search_posts(): void
    {
        Post::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Laravel est génial',
            'status' => 'published'
        ]);

        $response = $this->getJson('/api/posts/search?q=Laravel');

        $response->assertStatus(200)
            ->assertJsonPath('query', 'Laravel');
    }

    /**
     * Test de création avec image
     */
    public function test_can_create_post_with_image(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->postJson('/api/posts', [
                'title' => 'Article avec image',
                'content' => 'Ceci est un article avec une image. Le contenu doit faire au moins 50 caractères.',
                'image' => UploadedFile::fake()->image('test.jpg'),
            ]);

        $response->assertStatus(201);

        $post = Post::where('title', 'Article avec image')->first();
        $this->assertNotNull($post->image);
    }
}
