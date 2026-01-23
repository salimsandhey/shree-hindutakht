<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    /**
     * Display a listing of members with search and pagination
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 10);
        
        $query = Member::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('member_id', 'like', "%{$search}%");
            });
        }
        
        $members = $query->withCount(['posts', 'eventRsvps'])
                         ->orderBy('created_at', 'desc')
                         ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $members
        ]);
    }

    /**
     * Display the specified member
     */
    public function show(Member $member): JsonResponse
    {
        // Load relationships and counts
        $member->load(['posts', 'postComments', 'eventRsvps']);
        $member->posts_count = $member->posts()->count();
        $member->event_rsvps_count = $member->eventRsvps()->count();
        
        return response()->json([
            'success' => true,
            'data' => $member
        ]);
    }

    /**
     * Update the specified member
     */
    public function update(Request $request, Member $member): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:members,email,' . $member->id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'status' => 'sometimes|required|in:active,inactive,suspended',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $member->update($request->only([
            'name', 'email', 'phone', 'address', 'date_of_birth', 'gender', 'status'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Member updated successfully',
            'data' => $member->fresh()
        ]);
    }

    /**
     * Toggle member status (activate/deactivate)
     */
    public function toggleStatus(Member $member): JsonResponse
    {
        $newStatus = $member->status === 'active' ? 'inactive' : 'active';
        $member->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => "Member {$newStatus} successfully",
            'data' => $member->fresh()
        ]);
    }

    /**
     * Remove the specified member
     */
    public function destroy(Member $member): JsonResponse
    {
        // Delete related data (handled by model events)
        $member->delete();

        return response()->json([
            'success' => true,
            'message' => 'Member deleted successfully'
        ]);
    }

    /**
     * Get member statistics
     */
    public function stats(): JsonResponse
    {
        $totalMembers = Member::count();
        $activeMembers = Member::where('status', 'active')->count();
        $inactiveMembers = Member::where('status', 'inactive')->count();
        $suspendedMembers = Member::where('status', 'suspended')->count();
        
        $recentMembers = Member::orderBy('created_at', 'desc')
                               ->limit(5)
                               ->get(['id', 'name', 'email', 'member_id', 'created_at']);

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $totalMembers,
                'active' => $activeMembers,
                'inactive' => $inactiveMembers,
                'suspended' => $suspendedMembers,
                'recent' => $recentMembers
            ]
        ]);
    }
}