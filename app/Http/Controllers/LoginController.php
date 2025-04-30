<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required',
                'password' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }

        // Check if the password is correct
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid username or password'], 401);
        }

        // Generate JWT token for user
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status'=>'true',
            'message'=>'Login successful.',
            'token' => $token,
            'user' => $user
        ]);
    }
}
