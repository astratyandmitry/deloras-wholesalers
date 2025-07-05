<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wholesaler>
 */
final class WholesalerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName,
            'city' => $this->faker->city,
            'phone' => implode('', ['+7777', (string) rand(1000000, 9999999)]),
        ];
    }
}
