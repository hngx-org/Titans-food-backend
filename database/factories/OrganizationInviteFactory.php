<?php

namespace Database\Factories;

use App\Models\OrganizationInvite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrganizationInvite>
 */
class OrganizationInviteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = OrganizationInvite::class;

    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'token' => Str::random(10),
            'TTL' => $this->faker->dateTimeBetween('now', '+1 year'),
        ];
    }
}
