<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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

            'org_id' => fake()->numberBetween(1, 10),
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'profile_pic' => 'https://xsgames.co/randomusers/avatar.php?g=female', //randomly generate user profile photo
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('###########'),
            'password_hash' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // all fake user has this same password
            'is_admin' => fake()->boolean(),
            'lunch_credit_balance' => number_format(fake()->randomFloat(2, 1, 10000), 2),
            'refresh_token' => fake()->regexify('[A-Za-z0-9]{40}'),
            'bank_number' =>  fake()->numberBetween(1000, 9999),
            'bank_code' => fake()->numberBetween(100, 999),
            'bank_name' => fake()->company(),
            'bank_region' => fake()->country(),
            'currency' => fake()->numberBetween(100, 999),
            'currency_code' => 'NGN',

            'remember_token' => Str::random(10),
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
