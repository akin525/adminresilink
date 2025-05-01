<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'type' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // expects password_confirmation field too
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        $verification_token = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        // Create user
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'type' => $request->type,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Hash password
            'email_verified_at' => null,
            'verification_token' => $verification_token, // token to validate email
        ]);

        // Send email with verification token
        Mail::to($user->email)->send(new VerifyEmail($user));

        // Return response
        return response()->json([
            'status'=>'true',
            'message' => 'Registration successful. Check your email to verify your account.',
            'verification_token' => $verification_token,
            'user' => $user
        ], 200);
    }

    public function verifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return response()->json(['status'=>'false','message' => 'Invalid or expired token'], 400);
        }

        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();

        // Generate JWT token
        $token = JWTAuth::fromUser($user);


        return response()->json([
            'status'=>'true',
            'token' => $token,
            'data' => $user,
            'message' => 'Email successfully verified!'
        ], 200);
    }
    public function resendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['status' => 'false', 'message' => 'User not found'], 404);
        }

        if ($user->email_verified_at) {
            return response()->json(['status' => 'false', 'message' => 'Email already verified'], 400);
        }

        // Generate new token
        $user->verification_token = Str::random(60);
        $user->save();

        // Send verification email
        Mail::to($user->email)->send(new VerifyEmail($user));

        return response()->json([
            'status' => 'true',
            'message' => 'Verification code resent successfully.'
        ], 200);
    }


}
