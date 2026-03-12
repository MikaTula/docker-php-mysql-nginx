<?php

namespace Database\Factories;

use App\Models\Singer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Singer>
 */
class SingerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'age' => fake()->numberBetween(10, 90),
        ];
    }
}
