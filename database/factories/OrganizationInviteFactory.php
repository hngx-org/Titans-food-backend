<?php

namespace Database\Factories;

use App\Models\OrganizationInvite;
// use App\Models\Organization;
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
            'ttl' => $this->faker->dateTimeBetween('now', '+1 year'),
            // 'org_id' => Organization::create(),
            'org_id' => $this->faker->numberBetween(1, 10),
            'is_deleted' => $this->faker->boolean(),
        ];
    }
}
