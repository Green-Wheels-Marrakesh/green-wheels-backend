<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Bike;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BikeVariant>
 */
class BikeVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sizes = collect([
            'XS',
            'S',
            'M',
            'L',
            'XL',
            'XXL',
        ]);
        return  [
            'bike_size' => fake()->randomElement($sizes->toArray()),
            'article_id' => Article::factory(),
            'bike_id' => Bike::factory(),
        ];
    }
}
