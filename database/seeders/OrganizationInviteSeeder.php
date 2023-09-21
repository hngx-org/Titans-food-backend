<?php

namespace Database\Seeders;

use App\Models\OrganizationInvite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationInviteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrganizationInvite::factory()->count(10)->create();
    }
}
