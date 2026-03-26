<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Singer;
use App\Models\User;
use Illuminate\Database\Seeder;

class AlbumSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->orderBy('id')->each(function (User $user): void {
            $singer = Singer::query()
                ->where('created_by', $user->id)
                ->firstOrFail();

            Album::factory()->create([
                'title' => 'Album '.$user->id,
                'singer_id' => $singer->id,
                'created_by' => $user->id,
            ]);
        });
    }
}
