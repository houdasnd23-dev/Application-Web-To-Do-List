<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::with('role')->orderBy('name')->paginate(15);

        return response()->json($users);
    }

    public function updateRole(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'role_id' => ['required', 'integer', 'exists:roles,id'],
        ]);

        // Ajout de securite : un admin ne modifie pas son propre role
        if ($request->user()->id === $user->id) {
            return response()->json(
                ['message' => 'Vous ne pouvez pas modifier votre propre role.'], 403
            );
        }

        // role_id n'est pas assignable en masse : on l'affecte explicitement
        $user->role_id = $data['role_id'];
        $user->save();

        return response()->json($user->load('role'));
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        // Ajout de securite : un admin ne supprime pas son propre compte
        if ($request->user()->id === $user->id) {
            return response()->json(
                ['message' => 'Vous ne pouvez pas supprimer votre propre compte.'], 403
            );
        }

        $user->tokens()->delete();
        $user->delete();

        return response()->json(['message' => 'Utilisateur supprime.']);
    }
}