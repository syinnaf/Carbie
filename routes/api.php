<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TodoController;
use App\Http\Controllers\Api\SummaryController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\QuestController;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/categories', [CategoryController::class, 'index']);

// Routes that work for both authenticated and guest users
Route::middleware(['web'])->group(function () {
    Route::get('/todos', [TodoController::class, 'index']);
    Route::post('/todos', [TodoController::class, 'store']);
    Route::get('/todos/{todo}', [TodoController::class, 'show']);
    Route::put('/todos/{todo}', [TodoController::class, 'update']);
    Route::delete('/todos/{todo}', [TodoController::class, 'destroy']);
    Route::get('user/tasks', 'CarbonController@tasks');
    Route::get('user/carbon/today', 'CarbonController@today');

    Route::get('/summary/daily', [SummaryController::class, 'daily']);
    Route::get('/summary', [SummaryController::class, 'summary']);
    Route::get('/recommendations', [RecommendationController::class, 'index']);
});

// Protected routes (require authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    Route::get('/level', [LevelController::class, 'index']);
    
    Route::get('/quests', [QuestController::class, 'index']);
    Route::post('/quests/{quest}/progress', [QuestController::class, 'updateProgress']);
    Route::post('/quests/{quest}/complete', [QuestController::class, 'complete']);
});



// // Protected routes
// Route::middleware('auth:sanctum')->group(function () {
//     // Auth routes
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::get('/profile', [AuthController::class, 'profile']);
//     Route::put('/profile', [AuthController::class, 'updateProfile']);
    
//     // Todo routes
//     Route::apiResource('todos', TodoController::class);
//     Route::post('todos/{todo}/complete', [TodoController::class, 'complete']);
    
//     // Carbon tracking routes
//     Route::get('/carbon/daily-report', [CarbonController::class, 'dailyReport']);
//     Route::get('/carbon/weekly-report', [CarbonController::class, 'weeklyReport']);
//     Route::get('/carbon/monthly-report', [CarbonController::class, 'monthlyReport']);
    
//     // Recommendations routes
//     Route::get('/recommendations/green', [RecommendationController::class, 'getGreenRecommendations']);
    
//     // Quest routes
//     Route::get('/quests', [QuestController::class, 'index']);
//     Route::post('/quests/generate-daily', [QuestController::class, 'generateDailyQuests']);
//     Route::post('/quests/{quest}/complete', [QuestController::class, 'completeQuest']);

//     // Dashboard
//     Route::get('/dashboard', [DashboardController::class, 'index']);
    
//     // Leaderboard
//     Route::get('/leaderboard', [LeaderboardController::class, 'index']);
    
//     // Additional quest routes
//     Route::get('/quests/available', [QuestController::class, 'getAvailableQuests']);
    
//     // Additional carbon routes
//     Route::post('/carbon/calculate', [CarbonController::class, 'calculateActivity']);
// });