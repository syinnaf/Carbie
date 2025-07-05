<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarbonActivity;
use App\Models\ActivityCategory;

class CarbonActivitySeeder extends Seeder
{
    public function run()
    {
        $goingOut = ActivityCategory::where('slug', 'going-out')->first();
        $homeActivity = ActivityCategory::where('slug', 'home-activity')->first();
        $onlineActivity = ActivityCategory::where('slug', 'online-activity')->first();
        $shopping = ActivityCategory::where('slug', 'shopping')->first();

        $activities = [
            // Going Out
            [
                'category_id' => $goingOut->id,
                'activity_type' => 'car',
                'unit' => 'km',
                'carbon_factor' => 0.21,
                'description' => 'Private car transportation',
            ],
            [
                'category_id' => $goingOut->id,
                'activity_type' => 'motorcycle',
                'unit' => 'km',
                'carbon_factor' => 0.15,
                'description' => 'Motorcycle transportation',
            ],
            [
                'category_id' => $goingOut->id,
                'activity_type' => 'bus',
                'unit' => 'km',
                'carbon_factor' => 0.08,
                'description' => 'Public bus transportation',
            ],
            [
                'category_id' => $goingOut->id,
                'activity_type' => 'train',
                'unit' => 'km',
                'carbon_factor' => 0.04,
                'description' => 'Train transportation',
            ],
            
            // Home Activity
            [
                'category_id' => $homeActivity->id,
                'activity_type' => 'air_conditioner',
                'unit' => 'hours',
                'carbon_factor' => 2.5,
                'description' => 'Air conditioner usage',
            ],
            [
                'category_id' => $homeActivity->id,
                'activity_type' => 'television',
                'unit' => 'hours',
                'carbon_factor' => 0.15,
                'description' => 'Television watching',
            ],
            [
                'category_id' => $homeActivity->id,
                'activity_type' => 'cooking',
                'unit' => 'hours',
                'carbon_factor' => 1.2,
                'description' => 'Cooking activities',
            ],
            
            // Online Activity
            [
                'category_id' => $onlineActivity->id,
                'activity_type' => 'smartphone',
                'unit' => 'hours',
                'carbon_factor' => 0.012,
                'description' => 'Smartphone usage',
            ],
            [
                'category_id' => $onlineActivity->id,
                'activity_type' => 'laptop',
                'unit' => 'hours',
                'carbon_factor' => 0.05,
                'description' => 'Laptop usage',
            ],
            [
                'category_id' => $onlineActivity->id,
                'activity_type' => 'desktop',
                'unit' => 'hours',
                'carbon_factor' => 0.15,
                'description' => 'Desktop computer usage',
            ],
            
            // Shopping
            [
                'category_id' => $shopping->id,
                'activity_type' => 'food',
                'unit' => 'kg',
                'carbon_factor' => 2.5,
                'description' => 'Food shopping',
            ],
            [
                'category_id' => $shopping->id,
                'activity_type' => 'clothing',
                'unit' => 'kg',
                'carbon_factor' => 15.0,
                'description' => 'Clothing shopping',
            ],
            [
                'category_id' => $shopping->id,
                'activity_type' => 'electronics',
                'unit' => 'kg',
                'carbon_factor' => 300.0,
                'description' => 'Electronics shopping',
            ],
        ];

        foreach ($activities as $activity) {
            CarbonActivity::create($activity);
        }
    }
}

