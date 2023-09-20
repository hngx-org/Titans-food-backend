<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Lunch;
use App\Models\User;
use Illuminate\Support\Str;

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
            'senderId' => User::factory(),
            'receiverId' => User::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'redeemed' => $this->faker->boolean,
            'note' => $this->faker->text,
        ];
    }
}
