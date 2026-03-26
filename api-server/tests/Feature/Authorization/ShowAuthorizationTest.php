<?php

namespace Tests\Feature\Authorization;

use App\Models\Genre;
use App\Models\Singer;
use App\Models\Song;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_own_song(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $singer = Singer::create([
            'first_name' => 'S',
            'last_name' => 'One',
            'age' => 25,
            'created_by' => $user->id,
        ]);

        $song = Song::create([
            'name' => 'Song A',
            'singer_id' => $singer->id,
            'year' => 2000,
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('songs.show', ['song' => $song->id]));

        $response->assertOk()->assertJson(['success' => true]);
    }

    public function test_user_cannot_view_foreign_song(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $foreignUser = User::factory()->create(['role' => 'user']);

        $singer = Singer::create([
            'first_name' => 'S',
            'last_name' => 'One',
            'age' => 25,
            'created_by' => $owner->id,
        ]);

        $song = Song::create([
            'name' => 'Song A',
            'singer_id' => $singer->id,
            'year' => 2000,
            'created_by' => $owner->id,
        ]);

        $response = $this->actingAs($foreignUser, 'sanctum')
            ->getJson(route('songs.show', ['song' => $song->id]));

        $response->assertForbidden();
    }

    public function test_admin_can_view_foreign_song(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);

        $singer = Singer::create([
            'first_name' => 'S',
            'last_name' => 'One',
            'age' => 25,
            'created_by' => $owner->id,
        ]);

        $song = Song::create([
            'name' => 'Song A',
            'singer_id' => $singer->id,
            'year' => 2000,
            'created_by' => $owner->id,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson(route('songs.show', ['song' => $song->id]));

        $response->assertOk()->assertJson(['success' => true]);
    }

    public function test_user_can_view_own_genre(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $genre = Genre::create(['name' => 'Rock', 'created_by' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('genres.show', ['genre' => $genre->id]));

        $response->assertOk()->assertJson(['success' => true]);
    }

    public function test_user_cannot_view_foreign_genre(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $foreignUser = User::factory()->create(['role' => 'user']);
        $genre = Genre::create(['name' => 'Rock', 'created_by' => $owner->id]);

        $response = $this->actingAs($foreignUser, 'sanctum')
            ->getJson(route('genres.show', ['genre' => $genre->id]));

        $response->assertForbidden();
    }

    public function test_admin_can_view_foreign_genre(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $genre = Genre::create(['name' => 'Rock', 'created_by' => $owner->id]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson(route('genres.show', ['genre' => $genre->id]));

        $response->assertOk()->assertJson(['success' => true]);
    }

    public function test_user_can_view_own_singer(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $singer = Singer::create([
            'first_name' => 'A',
            'last_name' => 'One',
            'age' => 20,
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('singers.show', ['singer' => $singer->id]));

        $response->assertOk()->assertJson(['success' => true]);
    }

    public function test_user_cannot_view_foreign_singer(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $foreignUser = User::factory()->create(['role' => 'user']);
        $singer = Singer::create([
            'first_name' => 'A',
            'last_name' => 'One',
            'age' => 20,
            'created_by' => $owner->id,
        ]);

        $response = $this->actingAs($foreignUser, 'sanctum')
            ->getJson(route('singers.show', ['singer' => $singer->id]));

        $response->assertForbidden();
    }

    public function test_admin_can_view_foreign_singer(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $singer = Singer::create([
            'first_name' => 'A',
            'last_name' => 'One',
            'age' => 20,
            'created_by' => $owner->id,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson(route('singers.show', ['singer' => $singer->id]));

        $response->assertOk()->assertJson(['success' => true]);
    }
}
