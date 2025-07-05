<?php
//NGAPAIN?
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityCategory;
use App\Models\CarbonActivity;

class CarbonActivitiesSeeder extends Seeder
{
    public function run()
    {
        // Seed categories
        $categories = [
            ['name' => 'Going Out', 'slug' => 'going-out', 'description' => 'Transportation and outdoor activities'],
            ['name' => 'Home Activity', 'slug' => 'home-activity', 'description' => 'Activities done at home'],
            ['name' => 'Online Activity', 'slug' => 'online-activity', 'description' => 'Digital and online activities'],
            ['name' => 'Buy Stuff', 'slug' => 'buy-stuff', 'description' => 'Shopping and purchases'],
        ];

        foreach ($categories as $category) {
            ActivityCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        // Get category IDs
        $goingOutCategory = ActivityCategory::where('slug', 'going-out')->first();
        $homeActivityCategory = ActivityCategory::where('slug', 'home-activity')->first();
        $onlineActivityCategory = ActivityCategory::where('slug', 'online-activity')->first();
        $buyStuffCategory = ActivityCategory::where('slug', 'buy-stuff')->first();

        // Seed Going Out activities
        $goingOutActivities = [
            // Vehicles
            ['activity_type' => 'vehicle', 'activity_name' => 'Walking', 'unit' => 'km', 'carbon_factor' => 0, 'sub_category' => 'transportation'],
            ['activity_type' => 'vehicle', 'activity_name' => 'Bike', 'unit' => 'km', 'carbon_factor' => 0, 'sub_category' => 'transportation'],
            ['activity_type' => 'vehicle', 'activity_name' => 'Motorcycle', 'unit' => 'km', 'carbon_factor' => 0.1, 'sub_category' => 'transportation'],
            ['activity_type' => 'vehicle', 'activity_name' => 'Car', 'unit' => 'km', 'carbon_factor' => 0.16, 'sub_category' => 'transportation'],
            ['activity_type' => 'vehicle', 'activity_name' => 'Train', 'unit' => 'km', 'carbon_factor' => 0.04, 'sub_category' => 'transportation'],
            ['activity_type' => 'vehicle', 'activity_name' => 'Bus', 'unit' => 'km', 'carbon_factor' => 0.1, 'sub_category' => 'transportation'],
            ['activity_type' => 'vehicle', 'activity_name' => 'Airplane', 'unit' => 'km', 'carbon_factor' => 0.25, 'sub_category' => 'transportation'],
            ['activity_type' => 'vehicle', 'activity_name' => 'Ship', 'unit' => 'km', 'carbon_factor' => 0.18, 'sub_category' => 'transportation'],
            // Destinations
            ['activity_type' => 'destination', 'activity_name' => 'Destination', 'unit' => 'visit', 'carbon_factor' => 0, 'sub_category' => 'location'],
            ['activity_type' => 'occupation', 'activity_name' => 'Occupation', 'unit' => 'hour', 'carbon_factor' => 0, 'sub_category' => 'activity'],
        ];

        foreach ($goingOutActivities as $activity) {
            CarbonActivity::updateOrCreate(
                [
                    'category_id' => $goingOutCategory->id,
                    'activity_type' => $activity['activity_type'],
                    'activity_name' => $activity['activity_name']
                ],
                array_merge($activity, ['category_id' => $goingOutCategory->id])
            );
        }

        // Seed Home Activity activities
        $homeActivities = [
            // Activities
            ['activity_type' => 'activity', 'activity_name' => 'Cleaning', 'unit' => 'hour', 'carbon_factor' => 0, 'sub_category' => 'household'],
            ['activity_type' => 'activity', 'activity_name' => 'Laundry', 'unit' => 'hour', 'carbon_factor' => 0, 'sub_category' => 'household'],
            ['activity_type' => 'activity', 'activity_name' => 'Cooking', 'unit' => 'hour', 'carbon_factor' => 0, 'sub_category' => 'household'],
            ['activity_type' => 'activity', 'activity_name' => 'Other', 'unit' => 'hour', 'carbon_factor' => 0, 'sub_category' => 'household'],
            // Electronics
            ['activity_type' => 'electronics', 'activity_name' => 'No Electronics', 'unit' => 'hour', 'carbon_factor' => 0, 'sub_category' => 'device'],
            ['activity_type' => 'electronics', 'activity_name' => 'Vacuum Cleaner', 'unit' => 'hour', 'carbon_factor' => 0.0143, 'sub_category' => 'device'],
            ['activity_type' => 'electronics', 'activity_name' => 'Washing Machine', 'unit' => 'hour', 'carbon_factor' => 0.0071, 'sub_category' => 'device'],
            ['activity_type' => 'electronics', 'activity_name' => 'Stove', 'unit' => 'hour', 'carbon_factor' => 0.0213, 'sub_category' => 'device'],
            ['activity_type' => 'electronics', 'activity_name' => 'AC', 'unit' => 'hour', 'carbon_factor' => 0.0012, 'sub_category' => 'device'],
            ['activity_type' => 'electronics', 'activity_name' => 'Fan', 'unit' => 'hour', 'carbon_factor' => 0.0016, 'sub_category' => 'device'],
            ['activity_type' => 'electronics', 'activity_name' => 'Television', 'unit' => 'hour', 'carbon_factor' => 0.001, 'sub_category' => 'device'],
            ['activity_type' => 'electronics', 'activity_name' => 'Other', 'unit' => 'hour', 'carbon_factor' => 0.018, 'sub_category' => 'device'],
            // Duration
            ['activity_type' => 'duration', 'activity_name' => 'How Long', 'unit' => 'minute', 'carbon_factor' => 0, 'sub_category' => 'time'],
        ];

        foreach ($homeActivities as $activity) {
            CarbonActivity::updateOrCreate(
                [
                    'category_id' => $homeActivityCategory->id,
                    'activity_type' => $activity['activity_type'],
                    'activity_name' => $activity['activity_name']
                ],
                array_merge($activity, ['category_id' => $homeActivityCategory->id])
            );
        }

        // Seed Online Activity activities
        $onlineActivities = [
            // Activities
            ['activity_type' => 'activity', 'activity_name' => 'Meeting online', 'unit' => 'hour', 'carbon_factor' => 0, 'sub_category' => 'online'],
            ['activity_type' => 'activity', 'activity_name' => 'Social Media', 'unit' => 'hour', 'carbon_factor' => 0, 'sub_category' => 'online'],
            ['activity_type' => 'activity', 'activity_name' => 'Call', 'unit' => 'hour', 'carbon_factor' => 0, 'sub_category' => 'online'],
            ['activity_type' => 'activity', 'activity_name' => 'Online Learning', 'unit' => 'hour', 'carbon_factor' => 0, 'sub_category' => 'online'],
            ['activity_type' => 'activity', 'activity_name' => 'Other', 'unit' => 'hour', 'carbon_factor' => 0, 'sub_category' => 'online'],
            // Devices
            ['activity_type' => 'device', 'activity_name' => 'Smartphone', 'unit' => 'hour', 'carbon_factor' => 0.0007, 'sub_category' => 'device'],
            ['activity_type' => 'device', 'activity_name' => 'Laptop', 'unit' => 'hour', 'carbon_factor' => 0.0021, 'sub_category' => 'device'],
            ['activity_type' => 'device', 'activity_name' => 'Tablet', 'unit' => 'hour', 'carbon_factor' => 0.0017, 'sub_category' => 'device'],
            ['activity_type' => 'device', 'activity_name' => 'PC', 'unit' => 'hour', 'carbon_factor' => 0, 'sub_category' => 'device'],
            ['activity_type' => 'device', 'activity_name' => 'Other', 'unit' => 'hour', 'carbon_factor' => 0, 'sub_category' => 'device'],
            // Duration
            ['activity_type' => 'duration', 'activity_name' => 'Duration', 'unit' => 'minute', 'carbon_factor' => 0, 'sub_category' => 'time'],
        ];

        foreach ($onlineActivities as $activity) {
            CarbonActivity::updateOrCreate(
                [
                    'category_id' => $onlineActivityCategory->id,
                    'activity_type' => $activity['activity_type'],
                    'activity_name' => $activity['activity_name']
                ],
                array_merge($activity, ['category_id' => $onlineActivityCategory->id])
            );
        }

        // Seed Buy Stuff activities
        $buyStuffActivities = [
            // Shopping types
            ['activity_type' => 'shopping', 'activity_name' => 'Food', 'unit' => 'kg', 'carbon_factor' => 0.009, 'sub_category' => 'category'],
            ['activity_type' => 'shopping', 'activity_name' => 'Fashion', 'unit' => 'kg', 'carbon_factor' => 0.12, 'sub_category' => 'category'],
            ['activity_type' => 'shopping', 'activity_name' => 'Electronics', 'unit' => 'kg', 'carbon_factor' => 0.008, 'sub_category' => 'category'],
            ['activity_type' => 'shopping', 'activity_name' => 'Other', 'unit' => 'kg', 'carbon_factor' => 0, 'sub_category' => 'category'],
            // Shopping methods
            ['activity_type' => 'method', 'activity_name' => 'Online Shopping (Journal)', 'unit' => 'kg', 'carbon_factor' => 0.027, 'sub_category' => 'method'],
            ['activity_type' => 'method', 'activity_name' => 'Online Shopping (Sources)', 'unit' => 'kg', 'carbon_factor' => 0.027, 'sub_category' => 'method'],
            ['activity_type' => 'method', 'activity_name' => 'Online Shopping (Transportation)', 'unit' => 'kg', 'carbon_factor' => 0.027, 'sub_category' => 'method'],
            ['activity_type' => 'method', 'activity_name' => 'Manual (Journal)', 'unit' => 'kg', 'carbon_factor' => 0.027, 'sub_category' => 'method'],
            ['activity_type' => 'method', 'activity_name' => 'Manual (Sources)', 'unit' => 'kg', 'carbon_factor' => 0.027, 'sub_category' => 'method'],
            ['activity_type' => 'method', 'activity_name' => 'Manual (Transportation)', 'unit' => 'kg', 'carbon_factor' => 0.027, 'sub_category' => 'method'],
            // Food items
            ['activity_type' => 'food', 'activity_name' => 'No Food', 'unit' => 'kg', 'carbon_factor' => 0.027, 'sub_category' => 'food'],
            ['activity_type' => 'food', 'activity_name' => 'Steak (Beef)', 'unit' => 'kg', 'carbon_factor' => 0.0008, 'sub_category' => 'food'],
            ['activity_type' => 'food', 'activity_name' => 'Chicken', 'unit' => 'kg', 'carbon_factor' => 0.0046, 'sub_category' => 'food'],
            ['activity_type' => 'food', 'activity_name' => 'Eggs', 'unit' => 'kg', 'carbon_factor' => 0.005, 'sub_category' => 'food'],
            ['activity_type' => 'food', 'activity_name' => 'Vegetables', 'unit' => 'kg', 'carbon_factor' => 0, 'sub_category' => 'food'],
            ['activity_type' => 'food', 'activity_name' => 'Other', 'unit' => 'kg', 'carbon_factor' => 0, 'sub_category' => 'food'],
            // Weight
            ['activity_type' => 'weight', 'activity_name' => 'Weight', 'unit' => 'kg', 'carbon_factor' => 0, 'sub_category' => 'measurement'],
        ];

        foreach ($buyStuffActivities as $activity) {
            CarbonActivity::updateOrCreate(
                [
                    'category_id' => $buyStuffCategory->id,
                    'activity_type' => $activity['activity_type'],
                    'activity_name' => $activity['activity_name']
                ],
                array_merge($activity, ['category_id' => $buyStuffCategory->id])
            );
        }

        $this->command->info('Carbon activities seeded successfully!');
    }
}