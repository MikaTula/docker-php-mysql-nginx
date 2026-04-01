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
        Genre::factory(10)->create([
            'created_by' => User::query()->firstOrFail()->id,
        ]);
    }
}
