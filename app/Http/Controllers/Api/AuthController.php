<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'email' => $request->email,
            'nickname' => $request->nickname,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 201);
    }

    public function login(LoginRequest $request)    
    {
    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
            'errors' => ['Email atau password salah']
        ], 401);
    }

    $user = Auth::user();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Login berhasil',
        'data' => [
            'user' => $user,
            'token' => $token,
        ],
    ]);
}

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'data' => ['message' => 'Logged out successfully'],
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    }
}

// namespace App\Http\Controllers;

// use App\Models\User;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;

// class AuthController extends Controller
// {
//     public function register(Request $request)
//     {
//         // nickname email password confirm_password
//         $validator->validator::make($request->all(),[
//             'nickname' => 'required|string|max:255',
//             'email' => 'required|string|email|max:255|unique:users',
//             'password' => 'required|string|min:8|confirmed',

//         ]);
    
//         if ($validator->fails()) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Validation errors',
//                 'errors' => $validator->errors()
//             ], 422);
//         }

//         $user = User::create([
//             'nickname' => $request->nickname,
//             'email' => $request->email,
//             'password' => Hash::make($request->password),
//         ]);

//         $token = $user->createToken('auth_token')->plainTextToken;

//         return response()->json([
//             'success' => true,
//             'message' => 'User registered successfully',
//             'data' => [
//                 'user' => $user,
//                 'token' => $token
//             ]
//         ], 201);
//     }

//     public function login(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'email' => 'required|email',
//             'password' => 'required',
//         ]);

//         if ($validator->fails()) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Validation errors',
//                 'errors' => $validator->errors()
//             ], 422);
//         }

//         $user = User::where('email', $request->email)->first();

//         if (!$user || !Hash::check($request->password, $user->password)) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Invalid credentials'
//             ], 401);
//         }

//         $token = $user->createToken('auth_token')->plainTextToken;

//         return response()->json([
//             'success' => true,
//             'message' => 'Login successful',
//             'data' => [
//                 'user' => $user,
//                 'token' => $token
//             ]
//         ]);
//     }

//     public function logout(Request $request)
//     {
//         $request->user()->currentAccessToken()->delete();

//         return response()->json([
//             'success' => true,
//             'message' => 'Logged out successfully'
//         ]);
//     }

//     public function profile(Request $request)
//     {
//         $user = $request->user();
//         $todayCarbon = $user->getDailyCarbonUsage();

//         return response()->json([
//             'success' => true,
//             'data' => [
//                 'user' => $user,
//                 'today_carbon_usage' => $todayCarbon,
//                 'carbon_percentage' => ($todayCarbon / $user->daily_carbon_limit) * 100
//             ]
//         ]);
//     }

//     public function updateProfile(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'name' => 'string|max:255',
//             'nickname' => 'nullable|string|max:255',
//             'daily_carbon_limit' => 'numeric|min:1|max:200',
//             'preferences' => 'array'
//         ]);

//         if ($validator->fails()) {
//             return response()->json([
//                 'success' => false,
//                 'errors' => $validator->errors()
//             ], 422);
//         }

//         $user = $request->user();
//         $user->update($request->only(['name', 'nickname', 'daily_carbon_limit', 'preferences']));

//         return response()->json([
//             'success' => true,
//             'message' => 'Profile updated successfully',
//             'data' => $user
//         ]);
//     }

//     public function guestProfile()
//     {
//         // For guest users - return default data
//         return response()->json([
//             'success' => true,
//             'data' => [
//                 'user' => [
//                     'name' => 'Guest User',
//                     'level' => 1,
//                     'experience_points' => 0,
//                     'daily_carbon_limit' => 50.0
//                 ],
//                 'today_carbon_usage' => 0,
//                 'carbon_percentage' => 0
//             ]
//         ]);
//     }
// }