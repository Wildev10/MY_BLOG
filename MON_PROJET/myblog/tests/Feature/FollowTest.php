<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FollowTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $userToFollow;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->userToFollow = User::factory()->create(['username' => 'johndoe']);
    }

    /**
     * Test de follow d'un utilisateur
     */
    public function test_user_can_follow_another_user(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson("/api/users/{$this->userToFollow->username}/follow");

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'following' => true
            ]);

        $this->assertDatabaseHas('follows', [
            'follower_id' => $this->user->id,
            'following_id' => $this->userToFollow->id
        ]);
    }

    /**
     * Test d'unfollow d'un utilisateur
     */
    public function test_user_can_unfollow(): void
    {
        Follow::create([
            'follower_id' => $this->user->id,
            'following_id' => $this->userToFollow->id
        ]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/users/{$this->userToFollow->username}/follow");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'following' => false
            ]);

        $this->assertDatabaseMissing('follows', [
            'follower_id' => $this->user->id,
            'following_id' => $this->userToFollow->id
        ]);
    }

    /**
     * Test qu'on ne peut pas se suivre soi-même
     */
    public function test_user_cannot_follow_self(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson("/api/users/{$this->user->username}/follow");

        $response->assertStatus(400);
    }

    /**
     * Test de listing des followers
     */
    public function test_can_list_followers(): void
    {
        $followers = User::factory()->count(3)->create();

        foreach ($followers as $follower) {
            Follow::create([
                'follower_id' => $follower->id,
                'following_id' => $this->userToFollow->id
            ]);
        }

        $response = $this->getJson("/api/users/{$this->userToFollow->username}/followers");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data.data');
    }

    /**
     * Test de listing des following
     */
    public function test_can_list_following(): void
    {
        $usersToFollow = User::factory()->count(3)->create();

        foreach ($usersToFollow as $userToFollow) {
            Follow::create([
                'follower_id' => $this->user->id,
                'following_id' => $userToFollow->id
            ]);
        }

        $response = $this->getJson("/api/users/{$this->user->username}/following");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data.data');
    }

    /**
     * Test du statut de follow
     */
    public function test_can_check_follow_status(): void
    {
        Follow::create([
            'follower_id' => $this->user->id,
            'following_id' => $this->userToFollow->id
        ]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/users/{$this->userToFollow->username}/follow/status");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'following' => true
            ]);
    }
}
