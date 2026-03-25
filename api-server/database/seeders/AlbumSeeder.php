<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\User;
use Illuminate\Database\Seeder;

class AlbumSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::query()->firstOrFail()->id;

        Album::factory(5)->create([
            'created_by' => $userId,
        ]);
    }
}
