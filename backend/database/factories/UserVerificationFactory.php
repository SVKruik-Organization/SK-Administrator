<?php

namespace Database\Factories;

use App\Enums\VerificationReason;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserVerification>
 */
class UserVerificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pin' => fake()->numberBetween(100000, 999999),
            'reason' => fake()->randomElement(array_map(fn ($case) => $case->name, VerificationReason::cases())),
            'expires_at' => fake()->dateTimeBetween('now', '+1 hour'),
        ];
    }
}
