<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(GenreSeeder::class);
        $this->call(SingerSeeder::class);
        $this->call(AlbumSeeder::class);
        $this->call(SongSeeder::class);
        $this->call(FileSeeder::class);
    }
}
