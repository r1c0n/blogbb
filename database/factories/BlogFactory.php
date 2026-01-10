<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->safeEmail(),
            'title' => fake()->sentence(rand(3, 8)),
            'body' => fake()->paragraphs(rand(3, 10), true),
            'ip' => fake()->ipv4(),
            'views' => fake()->numberBetween(0, 1000),
        ];
    }
}
