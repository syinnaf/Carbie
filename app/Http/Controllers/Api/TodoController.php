<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TodoRequest;
use App\Models\UserTodo;
use App\Services\CarbonCalculatorService;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    private $carbonCalculator;

    public function __construct(CarbonCalculatorService $carbonCalculator)
    {
        $this->carbonCalculator = $carbonCalculator;
    }

    public function index(Request $request)
    {
        $query = UserTodo::with('category');

        if ($request->user()) {
            $query->where('user_id', $request->user()->id);
        } else {
            $query->where('session_id', $request->session()->getId());
        }

        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        $todos = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $todos,
        ]);
    }

    public function store(TodoRequest $request)
    {
        $categorySlug = $request->category()->slug ?? '';
        $carbonValue = $this->carbonCalculator->calculateCarbon($categorySlug, $request->details);

        $todo = UserTodo::create([
            'user_id' => $request->user() ? $request->user()->id : null,
            'session_id' => $request->user() ? null : $request->session()->getId(),
            'title' => $request->title,
            'category_id' => $request->category_id,
            'details' => $request->details,
            'date' => $request->date,
            'status' => $request->status ?? 'pending',
            'carbon_value' => $carbonValue,
        ]);

        // Log carbon calculation
        $this->carbonCalculator->logCarbonCalculation(
            $todo->user_id,
            $todo->session_id,
            $todo->id,
            $todo->date,
            $carbonValue,
            $categorySlug,
            $request->details
        );

        return response()->json([
            'success' => true,
            'data' => $todo->load('category'),
        ], 201);
    }

    public function show(Request $request, UserTodo $todo)
    {
        $this->authorize('view', $todo);

        return response()->json([
            'success' => true,
            'data' => $todo->load('category'),
        ]);
    }

    public function update(TodoRequest $request, UserTodo $todo)
    {
        $this->authorize('update', $todo);

        $categorySlug = $request->category()->slug ?? '';
        $carbonValue = $this->carbonCalculator->calculateCarbon($categorySlug, $request->details);

        $todo->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'details' => $request->details,
            'date' => $request->date,
            'status' => $request->status ?? $todo->status,
            'carbon_value' => $carbonValue,
        ]);

        // Update carbon log
        $this->carbonCalculator->logCarbonCalculation(
            $todo->user_id,
            $todo->session_id,
            $todo->id,
            $todo->date,
            $carbonValue,
            $categorySlug,
            $request->details
        );

        return response()->json([
            'success' => true,
            'data' => $todo->load('category'),
        ]);
    }

    public function destroy(Request $request, UserTodo $todo)
    {
        $this->authorize('delete', $todo);

        $todo->delete();

        return response()->json([
            'success' => true,
            'data' => ['message' => 'Todo deleted successfully'],
        ]);
    }
}