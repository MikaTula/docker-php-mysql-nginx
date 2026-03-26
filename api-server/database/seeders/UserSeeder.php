<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'admin',
            'email' => 'test@example.com',
            'password' => 'Password1',
            'role' => 'admin',
        ]);

        for ($i = 1; $i <= 10; $i++) {
            User::factory()->create([
                'name' => 'user '.$i,
                'email' => 'user'.$i.'@example.com',
                'password' => 'Password1',
                'role' => 'user',
            ]);
        }
    }
}
