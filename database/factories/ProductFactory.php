<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'category_id' => Category::inRandomOrder()->first()->id,
            'name' => fake()->word(),
            'price_excluding_tax' => fake()->randomFloat(2, 10, 1000),
            'price_including_tax' => function (array $attributes) {
                return $attributes['price_excluding_tax'] * (1 + $attributes['tax_rate'] / 100);
            },
            'tax_rate' => fake()->randomFloat(2, 5, 25),
            'description' => fake()->paragraph(),
        ];
    }
}
