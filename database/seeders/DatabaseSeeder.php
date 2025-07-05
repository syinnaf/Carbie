<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            ActivityCategorySeeder::class,
            CarbonActivitySeeder::class,
            GreenRecommendationSeeder::class,
            QuestSeeder::class,
        ]);
    }
}