<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;

            // return response()->json([
            //     'message' => 'Login successful',
            //     'token' => $token,
            //     'user' => $user,
            // ]);



            if ($user->hasRole('admin')) {
                return redirect()->route('products.list');
            } else {
                return redirect()->route('products.list');
            }
        }

        // return response()->json(['message' => 'Invalid credentials'], 401);
    }


    public function logout(Request $request)
    {
        // Revoke tokens and log out the user
        $user = $request->user();
        $user->tokens()->delete(); // If using Sanctum
        Auth::logout();

        // Redirect to login page with a success message
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }


}
