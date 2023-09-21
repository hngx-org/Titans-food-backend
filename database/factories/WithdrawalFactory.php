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
    protected $model = \App\Models\Withdrawal::class;
   
    public function definition()
    {
    return [
              
                'user_id' => function () {
                    return \App\Models\User::factory()->create()->id;
                },
                'status' => $this->faker->randomElement(['redeemed', 'not_redeemed']),
                'amount' => $this->faker->randomFloat(2, 1, 10000),
                'created_at' => now(),
                'updated_at' => now(),
                'is_deleted' => $this->faker->boolean(), 
            ];
        }
}
