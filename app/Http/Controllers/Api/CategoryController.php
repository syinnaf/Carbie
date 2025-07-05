<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = ActivityCategory::all();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }
}
