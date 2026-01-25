<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ScheduledTaskStatus;
use App\Enums\ScheduledTaskType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScheduledTask>
 */
class ScheduledTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(ScheduledTaskType::cases()),
            'status' => fake()->randomElement(ScheduledTaskStatus::cases()),
            'scheduled_at' => fake()->dateTimeBetween('now', '+1 month'),
            'tries' => fake()->numberBetween(0, 10),
        ];
    }
}
