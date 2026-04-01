<?php

namespace Tests\Feature\Authorization;

use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenreAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_own_genre(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $genre = Genre::create([
            'name' => 'Genre A',
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')->putJson(route('genres.update', ['genre' => $genre->id]), [
            'name' => 'Genre A updated',
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseHas('genres', ['id' => $genre->id, 'name' => 'Genre A updated']);
    }

    public function test_user_cannot_update_foreign_genre(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $foreignUser = User::factory()->create(['role' => 'user']);

        $foreignGenre = Genre::create([
            'name' => 'Foreign Genre',
            'created_by' => $foreignUser->id,
        ]);

        $response = $this->actingAs($owner, 'sanctum')->putJson(route('genres.update', ['genre' => $foreignGenre->id]), [
            'name' => 'Should not be updated',
        ]);

        $response->assertStatus(403)->assertJsonStructure(['errors']);
        $this->assertDatabaseHas('genres', ['id' => $foreignGenre->id, 'name' => 'Foreign Genre']);
    }

    public function test_admin_can_update_foreign_genre(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $foreignUser = User::factory()->create(['role' => 'user']);

        $foreignGenre = Genre::create([
            'name' => 'Foreign Genre',
            'created_by' => $foreignUser->id,
        ]);

        $response = $this->actingAs($admin, 'sanctum')->putJson(route('genres.update', ['genre' => $foreignGenre->id]), [
            'name' => 'Updated by admin',
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseHas('genres', ['id' => $foreignGenre->id, 'name' => 'Updated by admin']);
    }

    public function test_user_can_delete_own_genre(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $genre = Genre::create([
            'name' => 'Genre A',
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')->deleteJson(route('genres.destroy', ['genre' => $genre->id]));

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseMissing('genres', ['id' => $genre->id]);
    }

    public function test_user_cannot_delete_foreign_genre(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $foreignUser = User::factory()->create(['role' => 'user']);

        $foreignGenre = Genre::create([
            'name' => 'Foreign Genre',
            'created_by' => $foreignUser->id,
        ]);

        $response = $this->actingAs($owner, 'sanctum')->deleteJson(route('genres.destroy', ['genre' => $foreignGenre->id]));

        $response->assertStatus(403)->assertJsonStructure(['errors']);
        $this->assertDatabaseHas('genres', ['id' => $foreignGenre->id]);
    }

    public function test_admin_can_delete_foreign_genre(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $foreignUser = User::factory()->create(['role' => 'user']);

        $foreignGenre = Genre::create([
            'name' => 'Foreign Genre',
            'created_by' => $foreignUser->id,
        ]);

        $response = $this->actingAs($admin, 'sanctum')->deleteJson(route('genres.destroy', ['genre' => $foreignGenre->id]));

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseMissing('genres', ['id' => $foreignGenre->id]);
    }
}
