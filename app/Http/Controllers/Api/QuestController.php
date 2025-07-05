<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quest;
use App\Models\UserQuest;
use Illuminate\Http\Request;

class QuestController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'errors' => ['Authentication required for quests'],
            ], 401);
        }

        $activeQuests = UserQuest::with('quest')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->get();

        $availableQuests = Quest::where('is_active', true)
            ->whereNotIn('id', $activeQuests->pluck('quest_id'))
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'active_quests' => $activeQuests,
                'available_quests' => $availableQuests,
            ],
        ]);
    }

    public function updateProgress(Request $request, Quest $quest)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'errors' => ['Authentication required'],
            ], 401);
        }

        $userQuest = UserQuest::where('user_id', $user->id)
            ->where('quest_id', $quest->id)
            ->where('status', 'active')
            ->first();

        if (!$userQuest) {
            // Create new user quest if not exists
            $userQuest = UserQuest::create([
                'user_id' => $user->id,
                'quest_id' => $quest->id,
                'status' => 'active',
                'progress' => 0,
                'started_at' => now(),
            ]);
        }

        // Calculate progress based on quest type
        $progress = $this->calculateQuestProgress($user, $quest);
        $userQuest->update(['progress' => $progress]);

        // Check if quest is completed
        if ($progress >= $quest->target_value) {
            $userQuest->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
            
            // Award experience
            $user->addExperience($quest->exp_reward);
        }

        return response()->json([
            'success' => true,
            'data' => $userQuest->load('quest'),
        ]);
    }

    public function complete(Request $request, Quest $quest)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'errors' => ['Authentication required'],
            ], 401);
        }

        $userQuest = UserQuest::where('user_id', $user->id)
            ->where('quest_id', $quest->id)
            ->where('status', 'active')
            ->first();

        if (!$userQuest) {
            return response()->json([
                'success' => false,
                'errors' => ['Quest not found or already completed'],
            ], 404);
        }

        if ($userQuest->progress < $quest->target_value) {
            return response()->json([
                'success' => false,
                'errors' => ['Quest requirements not met'],
            ], 400);
        }

        $userQuest->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $user->addExperience($quest->exp_reward);

        return response()->json([
            'success' => true,
            'data' => [
                'quest' => $userQuest->load('quest'),
                'exp_gained' => $quest->exp_reward,
                'new_level' => $user->level,
                'new_exp' => $user->exp,
            ],
        ]);
    }

    private function calculateQuestProgress($user, $quest)
    {
        switch ($quest->type) {
            case 'daily_carbon':
                return $user->carbonLogs()->whereDate('date', now())->sum('carbon_amount');
            case 'weekly_carbon':
                return $user->carbonLogs()->whereBetween('date', [now()->startOfWeek(), now()])->sum('carbon_amount');
            case 'monthly_carbon':
                return $user->carbonLogs()->whereBetween('date', [now()->startOfMonth(), now()])->sum('carbon_amount');
            case 'task_completion':
                return $user->todos()->where('status', 'done')->whereBetween('date', [now()->startOfWeek(), now()])->count();
            default:
                return 0;
        }
    }
}