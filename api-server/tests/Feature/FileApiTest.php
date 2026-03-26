<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Singer;
use App\Models\Song;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_upload_file_with_description_and_song_id(): void
    {
        Storage::fake('local');

        $user = User::factory()->create(['role' => 'user']);
        $singer = Singer::create([
            'first_name' => 'A',
            'last_name' => 'B',
            'age' => 30,
            'created_by' => $user->id,
        ]);
        $song = Song::create([
            'name' => 'Song',
            'singer_id' => $singer->id,
            'year' => 2000,
            'created_by' => $user->id,
        ]);

        $upload = UploadedFile::fake()->create('notes.txt', 50);

        $response = $this->actingAs($user, 'sanctum')->post('/api/files', [
            'file' => $upload,
            'description' => 'Cover art draft',
            'song_id' => $song->id,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.description', 'Cover art draft')
            ->assertJsonPath('data.songId', $song->id);

        $this->assertDatabaseHas('files', [
            'user_id' => $user->id,
            'description' => 'Cover art draft',
            'original_name' => 'notes.txt',
        ]);

        $this->assertDatabaseHas('songs', [
            'id' => $song->id,
            'file_id' => $response->json('data.id'),
        ]);
    }

    public function test_owner_can_update_description(): void
    {
        Storage::fake('local');

        $user = User::factory()->create(['role' => 'user']);
        $path = UploadedFile::fake()->create('x.txt', 10)->store('uploads/'.$user->id, 'local');

        $file = File::create([
            'user_id' => $user->id,
            'description' => 'old',
            'disk' => 'local',
            'path' => $path,
            'original_name' => 'x.txt',
            'mime_type' => 'text/plain',
            'size' => 10,
        ]);

        $response = $this->actingAs($user, 'sanctum')->patchJson('/api/files/'.$file->id, [
            'description' => 'new text',
        ]);

        $response->assertStatus(200)->assertJsonPath('data.description', 'new text');
    }

    public function test_non_owner_cannot_delete_file(): void
    {
        Storage::fake('local');

        $owner = User::factory()->create(['role' => 'user']);
        $other = User::factory()->create(['role' => 'user']);

        $path = UploadedFile::fake()->create('x.txt', 10)->store('uploads/'.$owner->id, 'local');

        $file = File::create([
            'user_id' => $owner->id,
            'description' => null,
            'disk' => 'local',
            'path' => $path,
            'original_name' => 'x.txt',
            'mime_type' => 'text/plain',
            'size' => 10,
        ]);

        $response = $this->actingAs($other, 'sanctum')->deleteJson('/api/files/'.$file->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('files', ['id' => $file->id]);
    }

    public function test_deleting_song_removes_linked_file_from_disk_and_database(): void
    {
        Storage::fake('local');

        $user = User::factory()->create(['role' => 'user']);
        $singer = Singer::create([
            'first_name' => 'A',
            'last_name' => 'B',
            'age' => 30,
            'created_by' => $user->id,
        ]);
        $song = Song::create([
            'name' => 'Song',
            'singer_id' => $singer->id,
            'year' => 2000,
            'created_by' => $user->id,
        ]);

        $upload = UploadedFile::fake()->create('only.txt', 20);
        $store = $this->actingAs($user, 'sanctum')->post('/api/files', [
            'file' => $upload,
            'song_id' => $song->id,
        ]);

        $store->assertStatus(200);
        $fileId = $store->json('data.id');
        $file = File::query()->findOrFail($fileId);

        Storage::disk('local')->assertExists($file->path);

        $deleteSong = $this->actingAs($user, 'sanctum')->deleteJson('/api/songs/'.$song->id);
        $deleteSong->assertStatus(200);

        $this->assertDatabaseMissing('files', ['id' => $fileId]);
        Storage::disk('local')->assertMissing($file->path);
    }

    public function test_uploading_file_to_song_replaces_previous_file_for_that_song(): void
    {
        Storage::fake('local');

        $user = User::factory()->create(['role' => 'user']);
        $singer = Singer::create([
            'first_name' => 'A',
            'last_name' => 'B',
            'age' => 30,
            'created_by' => $user->id,
        ]);
        $song = Song::create([
            'name' => 'Song',
            'singer_id' => $singer->id,
            'year' => 2000,
            'created_by' => $user->id,
        ]);

        $first = $this->actingAs($user, 'sanctum')->post('/api/files', [
            'file' => UploadedFile::fake()->create('a.txt', 10),
            'song_id' => $song->id,
        ]);
        $first->assertStatus(200);
        $firstId = $first->json('data.id');
        $firstPath = File::query()->findOrFail($firstId)->path;

        $second = $this->actingAs($user, 'sanctum')->post('/api/files', [
            'file' => UploadedFile::fake()->create('b.txt', 10),
            'song_id' => $song->id,
        ]);
        $second->assertStatus(200);
        $secondId = $second->json('data.id');

        $this->assertDatabaseMissing('files', ['id' => $firstId]);
        Storage::disk('local')->assertMissing($firstPath);
        $this->assertDatabaseHas('songs', ['id' => $song->id, 'file_id' => $secondId]);
    }
}
