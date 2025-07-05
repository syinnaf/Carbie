<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'errors' => ['Authentication required for level tracking'],
            ], 401);
        }

        $nextLevelExp = $user->level * 100;
        $progressPercentage = ($user->exp / $nextLevelExp) * 100;

        return response()->json([
            'success' => true,
            'data' => [
                'level' => $user->level,
                'current_exp' => $user->exp,
                'next_level_exp' => $nextLevelExp,
                'progress_percentage' => round($progressPercentage, 2),
            ],
        ]);
    }
}
