<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventLog>
 */
class EventLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => fake()->sentence(),
            'endpoint' => fake()->url(),
            'method' => fake()->randomElement(['GET', 'POST', 'PUT', 'DELETE']),
            'ip_address' => fake()->ipv4(),
        ];
    }
}
