<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GreenRecommendation;
use App\Models\ActivityCategory;

class GreenRecommendationSeeder extends Seeder
{
    public function run()
    {
        $goingOut = ActivityCategory::where('slug', 'going-out')->first();
        $homeActivity = ActivityCategory::where('slug', 'home-activity')->first();
        $onlineActivity = ActivityCategory::where('slug', 'online-activity')->first();
        $shopping = ActivityCategory::where('slug', 'shopping')->first();

        $recommendations = [
            [
                'title' => 'Try Public Transportation',
                'description' => 'Consider using bus or train instead of private car to reduce carbon emissions.',
                'category_id' => $goingOut->id,
                'carbon_threshold' => 10.0,
                'priority' => 5,
            ],
            [
                'title' => 'Walk or Bike for Short Distances',
                'description' => 'For trips under 5km, walking or cycling is both healthy and eco-friendly.',
                'category_id' => $goingOut->id,
                'carbon_threshold' => 5.0,
                'priority' => 4,
            ],
            [
                'title' => 'Use Energy-Efficient Appliances',
                'description' => 'Switch to LED lights and energy-efficient appliances to reduce home energy consumption.',
                'category_id' => $homeActivity->id,
                'carbon_threshold' => 15.0,
                'priority' => 3,
            ],
            [
                'title' => 'Reduce Screen Time',
                'description' => 'Limit unnecessary device usage to reduce digital carbon footprint.',
                'category_id' => $onlineActivity->id,
                'carbon_threshold' => 2.0,
                'priority' => 2,
            ],
            [
                'title' => 'Buy Local and Seasonal',
                'description' => 'Choose locally produced and seasonal items to reduce transportation emissions.',
                'category_id' => $shopping->id,
                'carbon_threshold' => 20.0,
                'priority' => 4,
            ],
            [
                'title' => 'Reduce, Reuse, Recycle',
                'description' => 'Before buying new items, consider if you can reuse or recycle existing ones.',
                'category_id' => $shopping->id,
                'carbon_threshold' => 30.0,
                'priority' => 5,
            ],
        ];

        foreach ($recommendations as $recommendation) {
            GreenRecommendation::create($recommendation);
        }
    }
}

