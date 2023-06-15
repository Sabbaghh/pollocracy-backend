<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vote>
 */
class VoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'candidate_id' => fake()->numberBetween(1, 4),
            'year' => fake()->numberBetween(2022, 2023),
            'month' => fake()->numberBetween(5, 6),
        ];
    }
}
