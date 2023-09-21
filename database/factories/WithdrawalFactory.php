<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Withdrawal>
 */
class WithdrawalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // protected $model = \App\Models\Withdrawal::class;
   
    public function definition(): array
    {
    return [
              
                'user_id' => function () {
                    return \App\Models\User::factory()->create()->id;
                },
                'status' => fake()->randomElement(['redeemed', 'not_redeemed']),
                'amount' => fake()->numberBetween(1000, 9999),
                'created_at' => now(),
                'updated_at' => now(),
                'is_deleted' => fake()->boolean(), 
            ];
        }
}
