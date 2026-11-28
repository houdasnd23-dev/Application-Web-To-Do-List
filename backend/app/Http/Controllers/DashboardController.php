<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user()->load('role');

        $data = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role?->name,
            ],
            'unread_notifications' => $user->appNotifications()
                ->whereNull('read_at')
                ->count(),
        ];

        // Statistiques globales : reservees a l'admin
        if ($user->role?->name === 'admin') {
            $data['users_total'] = User::count();
            $data['users_by_role'] = Role::withCount('users')
                ->get()
                ->pluck('users_count', 'name');
        }

        return response()->json($data);
    }
}