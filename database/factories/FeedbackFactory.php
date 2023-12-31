<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feedback>
 */
class FeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 4),
            'candidate_id' => fake()->numberBetween(1, 4),
            'feedback' => fake()->paragraph(),
            'feedback_reply' => fake()->paragraph(),
            'anonymous' => fake()->boolean(),
            'public' => fake()->boolean(),
        ];
    }
}
