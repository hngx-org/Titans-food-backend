<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Organization;
use \App\Models\User;
use \App\Models\Withdrawal;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            OrganizationSeeder::class,
            OrganizationLunchWalletSeeder::class
            // OrganizationSeeder::class,
            // UserSeeder::class,
            // WithdrawalSeeder::class,
        ]);
    }
}
