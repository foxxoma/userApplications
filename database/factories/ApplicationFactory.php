<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text('10'),
            'status' => 'Active',
            'message' => fake()->text('10'),
            'comment' => fake()->text('10'),
            'email' => fake()->unique()->safeEmail(),
        ];
    }
}
