<?php

namespace Database\Seeders;

use App\Models\BikeVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BikeVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countBikes = 5;
        BikeVariant::factory()
            ->count($countBikes)
            ->create();
    }
}
