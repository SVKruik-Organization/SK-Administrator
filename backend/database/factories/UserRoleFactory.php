<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserRole>
 */
class UserRoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => [
                'en' => fake()->jobTitle(),
                'nl' => fake()->jobTitle(),
            ],
            'description' => [
                'en' => fake()->sentence(),
                'nl' => fake()->sentence(),
            ],
            'position' => fake()->numberBetween(0, 100),
        ];
    }
}
