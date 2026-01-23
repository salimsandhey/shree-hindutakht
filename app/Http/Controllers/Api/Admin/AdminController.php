<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;

class AdminController extends Controller
{
    /**
     * Admin login
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        // Check if admin exists
        $admin = Admin::where('email', $credentials['email'])->first();
        
        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Check if admin is active
        if (!$admin->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Account is deactivated. Please contact super admin.'
            ], 403);
        }

        // Update last login
        $admin->update(['last_login' => now()]);

        // Create token
        $token = Auth::guard('admin')->login($admin);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'admin' => $admin,
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::guard('admin')->factory()->getTTL() * 60
            ]
        ]);
    }

    /**
     * Get authenticated admin profile
     */
    public function profile(): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
        
        return response()->json([
            'success' => true,
            'data' => $admin
        ]);
    }

    /**
     * Admin logout
     */
    public function logout(): JsonResponse
    {
        Auth::guard('admin')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Refresh token
     */
    public function refresh(): JsonResponse
    {
        $token = Auth::guard('admin')->refresh();

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::guard('admin')->factory()->getTTL() * 60
            ]
        ]);
    }
    /**
     * Get dashboard statistics
     */
    public function dashboardStats(): JsonResponse
    {
        try {
            $stats = [
                'total_members' => \App\Models\Member::count(),
                'active_members' => \App\Models\Member::where('status', 'active')->count(),
                'total_posts' => \App\Models\Post::count(),
                'total_events' => \App\Models\Event::count(),
                'upcoming_events' => \App\Models\Event::where('event_date', '>=', now())->count(),
                'total_news' => \App\Models\News::count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard stats',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}