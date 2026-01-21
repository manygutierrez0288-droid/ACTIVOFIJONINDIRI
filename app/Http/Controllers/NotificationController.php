<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Obtener las notificaciones del usuario autenticado.
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $unreadCount = Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * Marcar una notificación como leída.
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Marcar todas las notificaciones como leídas.
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Eliminar una notificación.
     */
    public function destroy($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->delete();

        return response()->json(['success' => true]);
    }
}
