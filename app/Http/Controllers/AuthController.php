<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    function dashboard()
    {
        $data['users'] = User::count();
        $data['property'] = Property::count();
        $data['property_sell'] = Property::where('mode', 'SALE')->count();
        $data['property_rent'] = Property::where('mode', 'RENT')->count();
        $data['tenant'] = Tenant::count();
        $data['revenue'] = Tenant::sum('amount');
        $data['recent_property'] = Property::orderBy('id', 'desc')
            ->limit(10)
            ->get();;
        // Get monthly stats
        $monthlyStats = Tenant::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(amount) as total_revenue'),
            DB::raw('COUNT(id) as total_sales')
        )
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get()
            ->keyBy('month');

        $monthlyRevenue = [];
        $monthlySales = [];

        foreach (range(1, 12) as $month) {
            $monthlyRevenue[] = $monthlyStats[$month]->total_revenue ?? 0;
            $monthlySales[] = $monthlyStats[$month]->total_sales ?? 0;
        }

        $data['monthlyRevenue'] = $monthlyRevenue;
        $data['monthlySales'] = $monthlySales;

        return view('dashboard', compact('data'));
    }

    public function profile(Request $request)
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is invalid or expired'], 401);
        }

        return response()->json([
            'status' => 'true',
            'message' => 'Profile fetched successfully',
            'data' => $user
        ]);
    }
    public function login(Request $request)
    {
        $this->validate($request,
            [
                'email' => 'required',
                'password' => 'required',
            ]);

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
            'message'=>'Agents Fetched',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function register(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // expects password_confirmation field too
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'type' => $request->type,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Hash password
        ]);

        // Generate JWT token
        $token = JWTAuth::fromUser($user);

        // Return response
        return response()->json([
            'message' => 'Registration successful',
            'token' => $token,
            'user' => $user
        ], 201);
    }
}
