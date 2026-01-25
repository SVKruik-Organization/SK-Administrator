<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModuleItemGrant>
 */
class ModuleItemGrantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'can_create' => fake()->boolean(),
            'can_read' => true,
            'can_update' => fake()->boolean(),
            'can_delete' => fake()->boolean(),
        ];
    }
}
