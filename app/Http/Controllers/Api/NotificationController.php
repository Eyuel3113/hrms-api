<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @group User Notifications
 * APIs for managing user notifications.
 */
class NotificationController extends Controller
{
    /**
     * Get Notifications
     * 
     * Get the authenticated user's notifications.
     * 
     * @group User Notifications
     * @queryParam page integer optional Page number. Default 1.
     * @response 200 {
     *   "message": "Notifications fetched successfully",
     *   "total": 5,
     *   "unread_count": 2,
     *   "data": [ ... ],
     *   "pagination": { ... }
     * }
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = $user->notifications()->latest()->paginate(10);

        $unreadCount = $user->unreadNotifications->count();

        // CLEAN RESPONSE WHEN EMPTY
        if ($notifications->isEmpty()) {
            return response()->json([
                'message' => 'No notifications found',
                'total' => 0,
                'unread_count' => $unreadCount,
                'data' => []
            ]);
        }

        return response()->json([
            'message' => 'Notifications fetched successfully',
            'total' => $notifications->total(),
            'unread_count' => $unreadCount,
            'data' => $notifications->items(),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
            ]
        ]);
    }

    /**
     * Mark as Read
     * 
     * Mark a specific notification as read.
     * 
     * @group User Notifications
     * @urlParam id string required The UUID of the notification.
     */
    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();
        $notification = $user->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        return response()->json(['message' => 'Notification marked as read']);
    }

    /**
     * Mark All as Read
     * 
     * Mark all unread notifications as read.
     * 
     * @group User Notifications
     */
    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json(['message' => 'All notifications marked as read']);
    }
}