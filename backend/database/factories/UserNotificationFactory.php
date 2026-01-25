<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PromptType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserNotification>
 */
class UserNotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(PromptType::cases()),
            'data' => [
                'message' => fake()->sentence(),
            ],
            'url' => fake()->url(),
            'is_silent' => fake()->boolean(),
            'expires_at' => fake()->dateTimeBetween('now', '+1 week'),
        ];
    }
}
