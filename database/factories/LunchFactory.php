<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lunch>
 */
class LunchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sender_id' => rand(1, 5),
            'receiver_id' => rand(1,5),
            'quantity' => rand(10, 100),
            'redeemed' => $this->faker->boolean,
            'note' => $this->faker->sentence()
        ];
    }
}
