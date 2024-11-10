<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    // User Registration
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:App\Models\User,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required',
            'fullname' => 'required',
        ]);

        // Create a new user and hash the password
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'fullname' => $request->fullname,
            'phone_num' => $request->phone_num,
            'office_location' => $request->office_location,
            'office_hours' => $request->office_hours,
            'department' => $request->department,
            'image' => $request->image,
        ]);

        return response()->json(['message' => 'User registered successfully!', 'user' => $user], 201);
    }

    // User Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // Check if the user exists and the password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create a new token for the user
        $token = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            'message' => 'Login successful!',
            'token' => $token,
            'user' => $user
        ], 200);
    }

    // User Logout
    public function logout(Request $request)
    {
        // Revoke all tokens for the authenticated user
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    // Get the authenticated user
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
