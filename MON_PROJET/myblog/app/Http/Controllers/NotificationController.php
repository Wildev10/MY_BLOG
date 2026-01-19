<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NotificationController extends Controller
{
    // GET /api/notifications - Lister les notifications de l'utilisateur
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->with(['fromUser:id,name', 'post:id,title'])
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $notifications
        ], Response::HTTP_OK);
    }

    // GET /api/notifications/unread-count - Nombre de notifications non lues
    public function unreadCount(Request $request)
    {
        $count = $request->user()->unreadNotifications()->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ], Response::HTTP_OK);
    }

    // POST /api/notifications/{id}/mark-as-read - Marquer comme lue
    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marquée comme lue'
        ], Response::HTTP_OK);
    }

    // POST /api/notifications/mark-all-as-read - Marquer toutes comme lues
    public function markAllAsRead(Request $request)
    {
        $request->user()
            ->unreadNotifications()
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Toutes les notifications ont été marquées comme lues'
        ], Response::HTTP_OK);
    }

    // DELETE /api/notifications/{id} - Supprimer une notification
    public function destroy(Request $request, $id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification supprimée'
        ], Response::HTTP_OK);
    }
}
