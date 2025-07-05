<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CarbonCalculatorService;
use App\Models\UserTodo;
use App\Models\UserCarbonLog;
use App\Models\ActivityCategory;
use App\Models\CarbonActivity;
use Carbon\Carbon;

class CarbonController extends Controller
{
    protected $carbonCalculatorService;

    public function __construct(CarbonCalculatorService $carbonCalculatorService)
    {
        $this->carbonCalculatorService = $carbonCalculatorService;
    }

    /**
     * Get user's tasks for today
     */
    public function getTodayTasks(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::now()->toDateString();

        $tasks = UserTodo::where('user_id', $user->id)
            ->where('date', $today)
            ->with(['category', 'carbonLogs'])
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'category' => $task->category->name ?? 'Other',
                    'completed' => $task->completed,
                    'carbon_amount' => $task->carbonLogs->sum('carbon_amount'),
                    'date' => $task->date,
                    'details' => $task->details
                ];
            });

        return response()->json([
            'success' => true,
            'tasks' => $tasks
        ]);
    }

    /**
     * Get total carbon for today
     */
    public function getTodayCarbon(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::now()->toDateString();

        $totalCarbon = $this->carbonCalculatorService->getTotalCarbonByDate($user->id, $today);

        return response()->json([
            'success' => true,
            'total_carbon' => $totalCarbon,
            'date' => $today
        ]);
    }

    /**
     * Create a new task
     */
    public function createTask(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:activity_categories,id',
            'date' => 'required|date',
            'details' => 'nullable|array'
        ]);

        $user = auth()->user();

        $task = UserTodo::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'category_id' => $request->category_id,
            'date' => $request->date,
            'details' => $request->details ?? [],
            'completed' => false
        ]);

        // Calculate carbon if task has details
        if ($request->details && !empty($request->details)) {
            $this->calculateAndLogCarbon($task);
        }

        return response()->json([
            'success' => true,
            'task' => $task,
            'message' => 'Task created successfully'
        ]);
    }

    /**
     * Update task status
     */
    public function updateTaskStatus(Request $request, $taskId)
    {
        $request->validate([
            'completed' => 'required|boolean'
        ]);

        $user = auth()->user();
        $task = UserTodo::where('user_id', $user->id)->findOrFail($taskId);

        $task->update([
            'completed' => $request->completed
        ]);

        // Recalculate carbon if task is completed and has details
        if ($request->completed && !empty($task->details)) {
            $this->calculateAndLogCarbon($task);
        }

        return response()->json([
            'success' => true,
            'task' => $task,
            'message' => 'Task status updated successfully'
        ]);
    }

    /**
     * Delete task
     */
    public function deleteTask(Request $request, $taskId)
    {
        $user = auth()->user();
        $task = UserTodo::where('user_id', $user->id)->findOrFail($taskId);

        // Delete associated carbon logs
        UserCarbonLog::where('todo_id', $task->id)->delete();

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }

    /**
     * Get activity categories
     */
    public function getCategories()
    {
        $categories = ActivityCategory::with('carbonActivities')->get();

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }

    /**
     * Get carbon activities by category
     */
    public function getCarbonActivities($categorySlug)
    {
        $category = ActivityCategory::where('slug', $categorySlug)->first();
        
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $activities = CarbonActivity::where('category_id', $category->id)
            ->get()
            ->groupBy('activity_type');

        return response()->json([
            'success' => true,
            'category' => $category,
            'activities' => $activities
        ]);
    }

    /**
     * Calculate carbon for a task
     */
    public function calculateTaskCarbon(Request $request, $taskId)
    {
        $request->validate([
            'details' => 'required|array'
        ]);

        $user = auth()->user();
        $task = UserTodo::where('user_id', $user->id)->findOrFail($taskId);

        // Update task details
        $task->update([
            'details' => $request->details
        ]);

        // Calculate and log carbon
        $carbonAmount = $this->calculateAndLogCarbon($task);

        return response()->json([
            'success' => true,
            'task' => $task,
            'carbon_amount' => $carbonAmount,
            'message' => 'Carbon calculated successfully'
        ]);
    }

    /**
     * Get carbon history
     */
    public function getCarbonHistory(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $history = $this->carbonCalculatorService->getCarbonHistoryByUser(
            $user->id, 
            $startDate, 
            $endDate
        );

        return response()->json([
            'success' => true,
            'history' => $history
        ]);
    }

    /**
     * Helper method to calculate and log carbon
     */
    private function calculateAndLogCarbon($task)
    {
        $category = $task->category;
        $details = $task->details;

        if (!$category || empty($details)) {
            return 0;
        }

        $carbonAmount = $this->carbonCalculatorService->calculateCarbon(
            $category->slug, 
            $details
        );

        // Delete existing carbon logs for this task
        UserCarbonLog::where('todo_id', $task->id)->delete();

        // Create new carbon log
        $this->carbonCalculatorService->logCarbonCalculation(
            $task->user_id,
            session()->getId(),
            $task->id,
            $task->date,
            $carbonAmount,
            $category->slug,
            $details
        );

        return $carbonAmount;
    }
}