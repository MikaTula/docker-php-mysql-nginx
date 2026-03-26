<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Seed the application's genres.
     */
    public function run(): void
    {
        User::query()->orderBy('id')->each(function (User $user): void {
            Genre::factory()->create([
                'name' => 'Genre '.$user->id,
                'created_by' => $user->id,
            ]);
        });
    }
}
