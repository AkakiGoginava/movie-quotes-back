<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        $notifications = Notification::forUser(Auth::id())
            ->with(['fromUser', 'quote'])
            ->latest()
            ->get();

        return response()->json([
            'data'         => NotificationResource::collection($notifications),
            'total_unread' => Notification::forUser(Auth::id())->unread()->count(),
        ]);
    }

    public function markAsRead(Notification $notification): JsonResponse
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function markAllAsRead(): JsonResponse
    {
        Notification::forUser(Auth::id())
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'All notifications marked as read']);
    }
}
