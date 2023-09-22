<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrganizationLunchWallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrganizationLunchWalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrganizationLunchWallet::factory()->count(1)->create();
    }
}
