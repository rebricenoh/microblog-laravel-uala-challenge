<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Domain\Entities\User;
use Illuminate\Support\Facades\DB;

class TweetControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear las tablas necesarias
        $this->artisan('migrate:fresh');
    }

    public function testCreateTweet(): void
    {
        // Crear un usuario directamente en la base de datos
        $userId = DB::table('users')->insertGetId([
            'name' => 'Test User',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $tweetData = [
            'user_id' => $userId,
            'content' => 'Test tweet content'
        ];

        $response = $this->postJson('/api/tweets', $tweetData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id',
                    'content',
                    'created_at'
                ]);
    }

    public function testCreateTweetWithInvalidContent(): void
    {
        $tweetData = [
            'user_id' => 1,
            'content' => str_repeat('a', 281)
        ];

        $response = $this->postJson('/api/tweets', $tweetData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['content']);
    }

    public function testGetTimeline(): void
    {
        // Crear dos usuarios
        $user1Id = DB::table('users')->insertGetId([
            'name' => 'User 1',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $user2Id = DB::table('users')->insertGetId([
            'name' => 'User 2',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Usuario 1 sigue a Usuario 2
        DB::table('follows')->insert([
            'follower_id' => $user1Id,
            'followed_id' => $user2Id,
            'created_at' => now()
        ]);

        // Usuario 2 crea un tweet
        DB::table('tweets')->insert([
            'user_id' => $user2Id,
            'content' => 'Test tweet',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Obtener timeline del Usuario 1
        $response = $this->getJson("/api/timeline?user_id={$user1Id}");

        $response->assertStatus(200)
                ->assertJsonCount(1);
    }
}
