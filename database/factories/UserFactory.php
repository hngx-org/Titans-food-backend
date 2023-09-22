<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            /* The user factory is working perfectly using php artisan db:seed, but note that the organization facory has to be ready due to foreign key contairans else it wont work
            but I have tested it without the org_id and it's working
            */

            'org_id' => function(){
                return Organization::factory()->create([
                ])->id;
            },
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'profile_pic' => 'https://xsgames.co/randomusers/avatar.php?g=female', //randomly generate user profile photo
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('###########'),
            'password_hash' => Hash::make('password'), // all fake user has this same password
            'is_admin' => fake()->boolean(),
            'lunch_credit_balance' => number_format(fake()->randomFloat(2, 1, 10000), 2),
            'refresh_token' => fake()->regexify('[A-Za-z0-9]{40}'),
            'bank_number' =>  fake()->numberBetween(1000, 9999),
            'bank_code' => fake()->numberBetween(100, 999),
            'bank_name' => fake()->company(),
            'bank_region' => fake()->country(),
            'currency' => fake()->numberBetween(100, 999),
            'currency_code' => 'NGN',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
