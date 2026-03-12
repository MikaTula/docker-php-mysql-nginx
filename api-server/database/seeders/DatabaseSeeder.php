<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Singer;
use App\Models\Song;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password1',
        ]);
        // User::factory(10)->create();

        $albumIds = Album::factory(5)->create()->each(function (Album $album) {
            return $album->id;
        });

        Singer::factory(10)->create()->each(function ($singer) {
            $singer->songs()->saveMany(Song::factory(rand(5, 10))->make());
        });

        Song::query()->get()->each(function (Song $song) use ($albumIds) {
            $song->album()->attach([$albumIds->random()]);
        });

        //        /** @var Collection<Song> $songs */
        //        $songs = $singer->songs();
    }
}
