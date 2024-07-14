<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return  [
            'default_selling_price' => fake()->randomFloat(2),
            'buying_price' => fake()->randomFloat(2),
            'default_booking_rental_price' => fake()->randomFloat(2),
            'default_booking_tour_price' => fake()->randomFloat(2),
            'default_guaranty_price' => fake()->randomFloat(2),
        ];
    }
}
