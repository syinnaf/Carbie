<?php

namespace App\Services;

use App\Models\CarbonActivity;
use App\Models\ActivityCategory;
use App\Models\UserCarbonLog;

class CarbonCalculatorService
{
    public function calculateCarbon(string $categorySlug, array $details): float
    {
        $category = ActivityCategory::where('slug', $categorySlug)->first();
        if (!$category) {
            return 0;
        }

        $carbonValue = 0;

        switch ($categorySlug) {
            case 'going-out':
                $carbonValue = $this->calculateGoingOutCarbon($details);
                break;
            case 'home-activity':
                $carbonValue = $this->calculateHomeActivityCarbon($details);
                break;
            case 'online-activity':
                $carbonValue = $this->calculateOnlineActivityCarbon($details);
                break;
            case 'buy-stuff':
                $carbonValue = $this->calculateBuyStuffCarbon($details);
                break;
        }

        return round($carbonValue, 2);
    }

    private function calculateGoingOutCarbon(array $details): float
    {
        $vehicle = $details['vehicle'] ?? '';
        $distance = (float) ($details['distance'] ?? 0);

        // Carbon factors berdasarkan data dari foto (kg CO2 per km)
        $carbonFactors = [
            'walking' => 0,
            'bike' => 0,
            'motorcycle' => 0.1,
            'car' => 0.16,
            'train' => 0.04,
            'bus' => 0.1,
            'airplane' => 0.25,
            'ship' => 0.18,
        ];

        $factor = $carbonFactors[$vehicle] ?? 0.16;
        return $distance * $factor;
    }

    private function calculateHomeActivityCarbon(array $details): float
    {
        $device = $details['device'] ?? '';
        $duration = (float) ($details['duration'] ?? 0); // dalam menit, konversi ke jam

        // Carbon factors berdasarkan data dari foto (kg CO2 per jam)
        $carbonFactors = [
            'cleaning' => 0,
            'laundry' => 0,
            'cooking' => 0,
            'other' => 0,
            'no_electronics' => 0,
            'vacuum_cleaner' => 0.0143,
            'washing_machine' => 0.0071,
            'stove' => 0.0213,
            'ac' => 0.0012,
            'fan' => 0.0016,
            'television' => 0.001,
            'other_electronic' => 0.018,
        ];

        $factor = $carbonFactors[$device] ?? 0;
        return ($duration / 60) * $factor; // konversi menit ke jam
    }

    private function calculateOnlineActivityCarbon(array $details): float
    {
        $activity = $details['activity'] ?? '';
        $duration = (float) ($details['duration'] ?? 0); // dalam menit

        // Carbon factors berdasarkan data dari foto (kg CO2 per jam)
        $carbonFactors = [
            'meeting_online' => 0,
            'social_media' => 0,
            'call' => 0,
            'online_learning' => 0,
            'other' => 0,
            'smartphone' => 0.0007,
            'laptop' => 0.0021,
            'tablet' => 0.0017,
            'pc' => 0,
            'other_device' => 0,
        ];

        $factor = $carbonFactors[$activity] ?? 0;
        return ($duration / 60) * $factor; // konversi menit ke jam
    }

    private function calculateBuyStuffCarbon(array $details): float
    {
        $category = $details['category'] ?? '';
        $weight = (float) ($details['weight'] ?? 0);

        // Carbon factors berdasarkan data dari foto (kg CO2 per kg)
        $carbonFactors = [
            'food' => 0.009,
            'fashion' => 0.12,
            'electronics' => 0.008,
            'other' => 0,
            'online_shopping_journal' => 0.027,
            'online_shopping_sources' => 0.027,
            'online_shopping_transportation' => 0.027,
            'manual_journal' => 0.027,
            'manual_sources' => 0.027,
            'manual_transportation' => 0.027,
            'no_food' => 0.027,
            'steak_beef' => 0.0008,
            'chicken' => 0.0046,
            'eggs' => 0.005,
            'vegetables' => 0,
            'other_food' => 0,
        ];

        $factor = $carbonFactors[$category] ?? 0;
        return $weight * $factor;
    }

    public function logCarbonCalculation($userId, $sessionId, $todoId, $date, $carbonAmount, $activityType, $details)
    {
        UserCarbonLog::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'todo_id' => $todoId,
            'date' => $date,
            'carbon_amount' => $carbonAmount,
            'activity_type' => $activityType,
            'calculation_details' => $details,
        ]);
    }

    public function getTotalCarbonByDate($userId, $date)
    {
        return UserCarbonLog::where('user_id', $userId)
            ->where('date', $date)
            ->sum('carbon_amount');
    }

    public function getCarbonHistoryByUser($userId, $startDate = null, $endDate = null)
    {
        $query = UserCarbonLog::where('user_id', $userId);
        
        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }
        
        return $query->orderBy('date', 'desc')->get();
    }
}