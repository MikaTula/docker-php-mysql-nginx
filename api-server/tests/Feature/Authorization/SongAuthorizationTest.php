<?php

namespace Tests\Feature\Authorization;

use App\Models\Singer;
use App\Models\Song;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SongAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_own_song(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $singer = Singer::create([
            'first_name' => 'Singer',
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

        $response = $this->actingAs($user, 'sanctum')->putJson(route('songs.update', ['song' => $song->id]), [
            'name' => 'Song A updated',
            'singer_id' => $singer->id,
            'year' => 2001,
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);
    }

    public function test_user_cannot_update_foreign_song(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $foreignUser = User::factory()->create(['role' => 'user']);

        $ownerSinger = Singer::create([
            'first_name' => 'Owner Singer',
            'last_name' => 'One',
            'age' => 30,
            'created_by' => $owner->id,
        ]);

        $foreignSinger = Singer::create([
            'first_name' => 'Foreign Singer',
            'last_name' => 'Two',
            'age' => 35,
            'created_by' => $foreignUser->id,
        ]);

        $foreignSong = Song::create([
            'name' => 'Foreign Song',
            'singer_id' => $foreignSinger->id,
            'year' => 1999,
            'created_by' => $foreignUser->id,
        ]);

        $response = $this->actingAs($owner, 'sanctum')->putJson(route('songs.update', ['song' => $foreignSong->id]), [
            'name' => 'Should not be updated',
            'singer_id' => $ownerSinger->id,
            'year' => 2005,
        ]);

        $response->assertStatus(403)->assertJsonStructure(['errors']);
    }

    public function test_admin_can_update_foreign_song(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $foreignUser = User::factory()->create(['role' => 'user']);

        $adminSinger = Singer::create([
            'first_name' => 'Admin Singer',
            'last_name' => 'One',
            'age' => 40,
            'created_by' => $admin->id,
        ]);

        $foreignSinger = Singer::create([
            'first_name' => 'Foreign Singer',
            'last_name' => 'Two',
            'age' => 41,
            'created_by' => $foreignUser->id,
        ]);

        $foreignSong = Song::create([
            'name' => 'Foreign Song',
            'singer_id' => $foreignSinger->id,
            'year' => 1980,
            'created_by' => $foreignUser->id,
        ]);

        $response = $this->actingAs($admin, 'sanctum')->putJson(route('songs.update', ['song' => $foreignSong->id]), [
            'name' => 'Updated by admin',
            'singer_id' => $adminSinger->id,
            'year' => 1981,
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);
    }

    public function test_user_can_delete_own_song(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $singer = Singer::create([
            'first_name' => 'Singer',
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

        $response = $this->actingAs($user, 'sanctum')->deleteJson(route('songs.destroy', ['song' => $song->id]));

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertSoftDeleted('songs', ['id' => $song->id]);
    }

    public function test_user_cannot_delete_foreign_song(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $foreignUser = User::factory()->create(['role' => 'user']);

        $foreignSinger = Singer::create([
            'first_name' => 'Foreign Singer',
            'last_name' => 'Two',
            'age' => 35,
            'created_by' => $foreignUser->id,
        ]);

        $foreignSong = Song::create([
            'name' => 'Foreign Song',
            'singer_id' => $foreignSinger->id,
            'year' => 1999,
            'created_by' => $foreignUser->id,
        ]);

        $response = $this->actingAs($owner, 'sanctum')->deleteJson(route('songs.destroy', ['song' => $foreignSong->id]));

        $response->assertStatus(403)->assertJsonStructure(['errors']);
        $this->assertDatabaseHas('songs', ['id' => $foreignSong->id]);
    }

    public function test_admin_can_delete_foreign_song(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $foreignUser = User::factory()->create(['role' => 'user']);

        $foreignSinger = Singer::create([
            'first_name' => 'Foreign Singer',
            'last_name' => 'Two',
            'age' => 35,
            'created_by' => $foreignUser->id,
        ]);

        $foreignSong = Song::create([
            'name' => 'Foreign Song',
            'singer_id' => $foreignSinger->id,
            'year' => 1999,
            'created_by' => $foreignUser->id,
        ]);

        $response = $this->actingAs($admin, 'sanctum')->deleteJson(route('songs.destroy', ['song' => $foreignSong->id]));

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertSoftDeleted('songs', ['id' => $foreignSong->id]);
    }
}
