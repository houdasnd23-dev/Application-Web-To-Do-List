<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->appNotifications()->latest();

        // Filtre optionnel : /api/notifications?unread=1
        if ($request->boolean('unread')) {
            $query->whereNull('read_at');
        }

        return response()->json($query->paginate(15));
    }

    public function unreadCount(Request $request): JsonResponse
    {
        $count = $request->user()->appNotifications()->whereNull('read_at')->count();

        return response()->json(['count' => $count]);
    }

    public function markAsRead(Request $request, int $id): JsonResponse
    {
        // On cherche uniquement parmi les notifications de l'utilisateur connecte :
        // celle d'un autre donne une 404, pas un acces
        $notification = $request->user()->appNotifications()->findOrFail($id);

        if ($notification->read_at === null) {
            $notification->update(['read_at' => now()]);
        }

        return response()->json($notification);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()->appNotifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Toutes les notifications sont lues.']);
    }
}