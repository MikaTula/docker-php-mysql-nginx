<?php

namespace Tests\Feature\Authorization;

use App\Models\Singer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SingerAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_own_singer(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $singer = Singer::create([
            'first_name' => 'Singer',
            'last_name' => 'One',
            'age' => 25,
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')->putJson(route('singers.update', ['singer' => $singer->id]), [
            'first_name' => 'Singer updated',
            'last_name' => 'One',
            'age' => 26,
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseHas('singers', ['id' => $singer->id, 'first_name' => 'Singer updated']);
    }

    public function test_user_cannot_update_foreign_singer(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $foreignUser = User::factory()->create(['role' => 'user']);

        $foreignSinger = Singer::create([
            'first_name' => 'Foreign Singer',
            'last_name' => 'Two',
            'age' => 35,
            'created_by' => $foreignUser->id,
        ]);

        $response = $this->actingAs($owner, 'sanctum')->putJson(route('singers.update', ['singer' => $foreignSinger->id]), [
            'first_name' => 'Should not be updated',
            'last_name' => 'Two',
            'age' => 36,
        ]);

        $response->assertStatus(403)->assertJsonStructure(['errors']);
        $this->assertDatabaseHas('singers', ['id' => $foreignSinger->id, 'first_name' => 'Foreign Singer']);
    }

    public function test_admin_can_update_foreign_singer(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $foreignUser = User::factory()->create(['role' => 'user']);

        $foreignSinger = Singer::create([
            'first_name' => 'Foreign Singer',
            'last_name' => 'Two',
            'age' => 35,
            'created_by' => $foreignUser->id,
        ]);

        $response = $this->actingAs($admin, 'sanctum')->putJson(route('singers.update', ['singer' => $foreignSinger->id]), [
            'first_name' => 'Updated by admin',
            'last_name' => 'Two',
            'age' => 36,
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseHas('singers', ['id' => $foreignSinger->id, 'first_name' => 'Updated by admin']);
    }

    public function test_user_can_delete_own_singer(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $singer = Singer::create([
            'first_name' => 'Singer',
            'last_name' => 'One',
            'age' => 25,
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')->deleteJson(route('singers.destroy', ['singer' => $singer->id]));

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertSoftDeleted('singers', ['id' => $singer->id]);
    }

    public function test_user_cannot_delete_foreign_singer(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $foreignUser = User::factory()->create(['role' => 'user']);

        $foreignSinger = Singer::create([
            'first_name' => 'Foreign Singer',
            'last_name' => 'Two',
            'age' => 35,
            'created_by' => $foreignUser->id,
        ]);

        $response = $this->actingAs($owner, 'sanctum')->deleteJson(route('singers.destroy', ['singer' => $foreignSinger->id]));

        $response->assertStatus(403)->assertJsonStructure(['errors']);
        $this->assertDatabaseHas('singers', ['id' => $foreignSinger->id]);
    }

    public function test_admin_can_delete_foreign_singer(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $foreignUser = User::factory()->create(['role' => 'user']);

        $foreignSinger = Singer::create([
            'first_name' => 'Foreign Singer',
            'last_name' => 'Two',
            'age' => 35,
            'created_by' => $foreignUser->id,
        ]);

        $response = $this->actingAs($admin, 'sanctum')->deleteJson(route('singers.destroy', ['singer' => $foreignSinger->id]));

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertSoftDeleted('singers', ['id' => $foreignSinger->id]);
    }
}
