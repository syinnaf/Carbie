<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quest;

class QuestSeeder extends Seeder
{
    public function run()
    {
        $quests = [
            [
                'title' => 'Green Day Champion',
                'description' => 'Keep your daily carbon footprint under 20kg CO₂',
                'target_value' => 20.0,
                'type' => 'daily_carbon',
                'exp_reward' => 50,
            ],
            [
                'title' => 'Eco Weekly Warrior',
                'description' => 'Maintain weekly carbon footprint under 100kg CO₂',
                'target_value' => 100.0,
                'type' => 'weekly_carbon',
                'exp_reward' => 200,
            ],
            [
                'title' => 'Monthly Carbon Master',
                'description' => 'Keep monthly carbon footprint under 400kg CO₂',
                'target_value' => 400.0,
                'type' => 'monthly_carbon',
                'exp_reward' => 500,
            ],
            [
                'title' => 'Task Completion Pro',
                'description' => 'Complete 20 tasks in a week',
                'target_value' => 20.0,
                'type' => 'task_completion',
                'exp_reward' => 100,
            ],
            [
                'title' => 'Consistency King',
                'description' => 'Keep daily carbon under 15kg CO₂',
                'target_value' => 15.0,
                'type' => 'daily_carbon',
                'exp_reward' => 75,
            ],
        ];

        foreach ($quests as $quest) {
            Quest::create($quest);
        }
    }
}