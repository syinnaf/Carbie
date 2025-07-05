<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserTodo;
use App\Models\UserCarbonLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SummaryController extends Controller
{
    public function daily(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $userId = $request->user() ? $request->user()->id : null;
        $sessionId = $request->user() ? null : $request->session()->getId();

        $query = UserTodo::with('category')->whereDate('date', $date);
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $todos = $query->get();
        $totalCarbon = $todos->sum('carbon_value');
        $completedTasks = $todos->where('status', 'done')->count();
        $totalTasks = $todos->count();

        return response()->json([
            'success' => true,
            'data' => [
                'date' => $date,
                'total_carbon' => $totalCarbon,
                'completed_tasks' => $completedTasks,
                'total_tasks' => $totalTasks,
                'tasks' => $todos,
            ],
        ]);
    }

    public function summary(Request $request)
    {
        $range = $request->get('range', 'weekly');
        $userId = $request->user() ? $request->user()->id : null;
        $sessionId = $request->user() ? null : $request->session()->getId();

        $startDate = $this->getStartDate($range);
        $endDate = now();

        $query = UserTodo::whereBetween('date', [$startDate, $endDate]);
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $todos = $query->get();
        $totalCarbon = $todos->sum('carbon_value');
        $totalTasks = $todos->count();
        $completedTasks = $todos->where('status', 'done')->count();
        $days = $startDate->diffInDays($endDate) + 1;
        $avgCarbonPerDay = $days > 0 ? $totalCarbon / $days : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'range' => $range,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'total_carbon' => $totalCarbon,
                'total_tasks' => $totalTasks,
                'completed_tasks' => $completedTasks,
                'avg_carbon_per_day' => round($avgCarbonPerDay, 2),
                'days' => $days,
            ],
        ]);
    }

    private function getStartDate(string $range): Carbon
    {
        switch ($range) {
            case 'weekly':
                return now()->startOfWeek();
            case 'monthly':
                return now()->startOfMonth();
            default:
                return now()->startOfWeek();
        }
    }
}