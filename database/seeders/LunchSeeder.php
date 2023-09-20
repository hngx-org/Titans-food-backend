<?php

namespace Database\Seeders;

use App\Models\Lunch;

use Illuminate\Database\Seeder;

class LunchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lunch::factory(5)->create();
    }
}
