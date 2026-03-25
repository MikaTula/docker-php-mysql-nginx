<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Genre;
use App\Models\Singer;
use App\Models\Song;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class SongSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::query()->firstOrFail()->id;

        $albumIds = Album::query()->pluck('id');
        $genreIds = Genre::query()->pluck('id');
        $singers = Singer::query()->get();

        $singers->each(function (Singer $singer) use ($userId, $albumIds, $genreIds): void {
            $songs = Song::factory(rand(5, 10))->make([
                'created_by' => $userId,
            ]);

            /** @var Collection<int, Song> $savedSongs */
            $savedSongs = $singer->songs()->saveMany($songs);

            $savedSongs->each(function (Song $song) use ($albumIds, $genreIds): void {
                $song->album()->attach([$albumIds->random()]);

                $count = min($genreIds->count(), fake()->numberBetween(1, 4));
                $picked = fake()->randomElements($genreIds->all(), $count);
                shuffle($picked);

                $song->genres()->sync($picked);
            });
        });
    }
}
