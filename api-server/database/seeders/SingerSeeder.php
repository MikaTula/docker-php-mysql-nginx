<?php

namespace Database\Seeders;

use App\Models\Singer;
use App\Models\User;
use Illuminate\Database\Seeder;

class SingerSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->orderBy('id')->each(function (User $user): void {
            Singer::factory()->create([
                'first_name' => 'Singer',
                'last_name' => 'User'.$user->id,
                'created_by' => $user->id,
            ]);
        });
    }
}
