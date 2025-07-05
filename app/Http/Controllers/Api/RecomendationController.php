<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GreenRecommendation;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function index(Request $request)
    {
        $carbonToday = (float) $request->get('carbon_today', 0);
        
        $recommendations = GreenRecommendation::with('category')
            ->where('is_active', true)
            ->where('carbon_threshold', '<=', $carbonToday)
            ->orderBy('priority', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'carbon_today' => $carbonToday,
                'recommendations' => $recommendations,
            ],
        ]);
    }
}