<?php

namespace Database\Factories;

use App\Enums\BikeStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bike>
 */
class BikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bikeTypes = collect([
            'type A',
            'type B',
            'type C',
            'type D',
        ]);
        $bikeMarks = collect([
            'MARK 1',
            'MARK 2',
            'MARK 3',
            'MARK 4',
        ]);
        $selectedBikeType = fake()->randomElement($bikeTypes->toArray());
        return  [
            'bike_type' => $selectedBikeType,
            'bike_model' => fake()->numerify($selectedBikeType . '-####'),
            'bike_mark' => fake()->randomElement($bikeMarks->toArray()),
            'bike_status' => fake()->randomElement(BikeStatusEnum::toValues()),
        ];
    }
}
