<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Get member's notifications
     */
    public function index(Request $request): JsonResponse
    {
        $member = auth('api')->user();
        $perPage = $request->get('per_page', 20);
        $type = $request->get('type'); // filter by type
        $unreadOnly = $request->get('unread_only', false);

        $query = Notification::where('member_id', $member->id)
            ->orderBy('created_at', 'desc');

        if ($type) {
            $query->byType($type);
        }

        if ($unreadOnly) {
            $query->unread();
        }

        $notifications = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification): JsonResponse
    {
        $member = auth('api')->user();
        
        if ($notification->member_id !== $member->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        $member = auth('api')->user();
        
        Notification::where('member_id', $member->id)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount(): JsonResponse
    {
        $member = auth('api')->user();
        
        $count = Notification::where('member_id', $member->id)
            ->unread()
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $count
            ]
        ]);
    }
}
