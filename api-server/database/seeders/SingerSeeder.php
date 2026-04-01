<?php

namespace Database\Seeders;

use App\Models\Singer;
use App\Models\User;
use Illuminate\Database\Seeder;

class SingerSeeder extends Seeder
{
    public function run(): void
    {
        Singer::factory(10)->create([
            'created_by' => User::query()->firstOrFail()->id,
        ]);
    }
}
