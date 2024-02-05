<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return  [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'cin' => fake()->numerify('EE-####'),
            'passport' => fake()->numerify('PassN-####'),
            'phone' => fake()->phoneNumber(),
            'contact_email' => fake()->companyEmail(),
            'city' => fake()->city(),
        ];;
    }
}
