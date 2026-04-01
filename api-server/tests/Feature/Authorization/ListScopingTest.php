<?php

namespace Tests\Feature\Authorization;

use App\Models\Genre;
use App\Models\Singer;
use App\Models\Song;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListScopingTest extends TestCase
{
    use RefreshDatabase;

    private function paginationQuery(): array
    {
        return [
            'page' => 1,
            'size' => 10,
            'sortBy' => 'id',
            'sortOrder' => 'asc',
        ];
    }

    public function test_user_sees_only_own_songs_in_index(): void
    {
        $userA = User::factory()->create(['role' => 'user']);
        $userB = User::factory()->create(['role' => 'user']);

        $singerA = Singer::create([
            'first_name' => 'A',
            'last_name' => 'One',
            'age' => 20,
            'created_by' => $userA->id,
        ]);
        $singerB = Singer::create([
            'first_name' => 'B',
            'last_name' => 'Two',
            'age' => 21,
            'created_by' => $userB->id,
        ]);

        Song::create([
            'name' => 'Song A',
            'singer_id' => $singerA->id,
            'year' => 2000,
            'created_by' => $userA->id,
        ]);
        Song::create([
            'name' => 'Song B',
            'singer_id' => $singerB->id,
            'year' => 2001,
            'created_by' => $userB->id,
        ]);

        $response = $this->actingAs($userA, 'sanctum')
            ->getJson(route('songs.index', $this->paginationQuery()));

        $response->assertOk()->assertJsonPath('data.total', 1);
        $this->assertCount(1, $response->json('data.items'));
        $this->assertSame('Song A', $response->json('data.items.0.name'));
    }

    public function test_admin_sees_all_songs_in_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $singerA = Singer::create([
            'first_name' => 'A',
            'last_name' => 'One',
            'age' => 20,
            'created_by' => $admin->id,
        ]);
        $singerB = Singer::create([
            'first_name' => 'B',
            'last_name' => 'Two',
            'age' => 21,
            'created_by' => $user->id,
        ]);

        Song::create([
            'name' => 'Song A',
            'singer_id' => $singerA->id,
            'year' => 2000,
            'created_by' => $admin->id,
        ]);
        Song::create([
            'name' => 'Song B',
            'singer_id' => $singerB->id,
            'year' => 2001,
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson(route('songs.index', $this->paginationQuery()));

        $response->assertOk()->assertJsonPath('data.total', 2);
        $this->assertCount(2, $response->json('data.items'));
    }

    public function test_user_sees_only_own_genres_in_index(): void
    {
        $userA = User::factory()->create(['role' => 'user']);
        $userB = User::factory()->create(['role' => 'user']);

        Genre::create(['name' => 'Rock', 'created_by' => $userA->id]);
        Genre::create(['name' => 'Jazz', 'created_by' => $userB->id]);

        $response = $this->actingAs($userA, 'sanctum')
            ->getJson(route('genres.index', $this->paginationQuery()));

        $response->assertOk()->assertJsonPath('data.total', 1);
        $this->assertSame('Rock', $response->json('data.items.0.name'));
    }

    public function test_admin_sees_all_genres_in_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        Genre::create(['name' => 'Rock', 'created_by' => $admin->id]);
        Genre::create(['name' => 'Jazz', 'created_by' => $user->id]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson(route('genres.index', $this->paginationQuery()));

        $response->assertOk()->assertJsonPath('data.total', 2);
    }

    public function test_user_sees_only_own_singers_in_index(): void
    {
        $userA = User::factory()->create(['role' => 'user']);
        $userB = User::factory()->create(['role' => 'user']);

        Singer::create([
            'first_name' => 'A',
            'last_name' => 'One',
            'age' => 20,
            'created_by' => $userA->id,
        ]);
        Singer::create([
            'first_name' => 'B',
            'last_name' => 'Two',
            'age' => 21,
            'created_by' => $userB->id,
        ]);

        $response = $this->actingAs($userA, 'sanctum')
            ->getJson(route('singers.index', $this->paginationQuery()));

        $response->assertOk()->assertJsonPath('data.total', 1);
        $this->assertSame('A', $response->json('data.items.0.firstName'));
    }

    public function test_admin_sees_all_singers_in_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        Singer::create([
            'first_name' => 'A',
            'last_name' => 'One',
            'age' => 20,
            'created_by' => $admin->id,
        ]);
        Singer::create([
            'first_name' => 'B',
            'last_name' => 'Two',
            'age' => 21,
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson(route('singers.index', $this->paginationQuery()));

        $response->assertOk()->assertJsonPath('data.total', 2);
    }
}
