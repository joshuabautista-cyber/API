<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Attempt to log in
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // In a real app, use Sanctum tokens here. For now, simple success is enough.
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'user' => $user
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}