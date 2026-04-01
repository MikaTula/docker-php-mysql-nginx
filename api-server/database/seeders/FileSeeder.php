<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Song;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class FileSeeder extends Seeder
{
    private const ORIGINAL_NAME = 'test-music.mp3';

    public function run(): void
    {
        User::query()->orderBy('id')->each(function (User $user): void {
            $relativePath = 'uploads/'.$user->id.'/'.self::ORIGINAL_NAME;

            Storage::disk('local')->put($relativePath, '');

            $file = File::query()->create([
                'user_id' => $user->id,
                'description' => 'Seeded placeholder audio',
                'disk' => 'local',
                'path' => $relativePath,
                'original_name' => self::ORIGINAL_NAME,
                'mime_type' => 'audio/mpeg',
                'size' => Storage::disk('local')->size($relativePath),
            ]);

            $song = Song::query()
                ->where('created_by', $user->id)
                ->whereNull('file_id')
                ->inRandomOrder()
                ->first();

            if ($song !== null) {
                $song->update(['file_id' => $file->id]);
            }
        });
    }
}
