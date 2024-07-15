<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'total_amount' => fake()->numberBetween(1000, 100000),
            'payment_intent_id' => fake()->uuid(),
            'status' => fake()->randomElement(['pending', 'completed', 'failed']),
            'currency' => fake()->currencyCode(),
        ];
    }
}
