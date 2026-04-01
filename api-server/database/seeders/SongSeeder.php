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
        User::query()->orderBy('id')->each(function (User $user): void {
            $albumIds = Album::query()
                ->where('created_by', $user->id)
                ->pluck('id');
            $genreIds = Genre::query()
                ->where('created_by', $user->id)
                ->pluck('id');

            Singer::query()
                ->where('created_by', $user->id)
                ->each(function (Singer $singer) use ($user, $albumIds, $genreIds): void {
                    $songs = Song::factory(rand(5, 10))->make([
                        'created_by' => $user->id,
                    ]);

                    /** @var Collection<int, Song> $savedSongs */
                    $savedSongs = $singer->songs()->saveMany($songs);

                    $savedSongs->each(function (Song $song) use ($albumIds, $genreIds): void {
                        $song->album()->attach([$albumIds->random()]);

                        $count = min($genreIds->count(), fake()->numberBetween(1, max(1, $genreIds->count())));
                        $picked = fake()->randomElements($genreIds->all(), $count);
                        shuffle($picked);

                        $song->genres()->sync($picked);
                    });
                });
        });
    }
}
