<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Le role est toujours "membre" : tout role envoye par le client est ignore
        $user = new User($data);
        $user->role_id = Role::where('name', 'membre')->firstOrFail()->id;
        $user->save();

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'user' => $user->load('role'),
            'token' => $token,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Identifiants invalides.'], 401);
        }

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'user' => $user->load('role'),
            'token' => $token,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        // Supprime uniquement le token utilise pour cette requete
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Deconnecte.']);
    }

    public function profile(Request $request): JsonResponse
    {
        return response()->json($request->user()->load('role'));
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'avatar' => ['nullable', 'string', 'max:255'],
            'current_password' => ['required_with:password', 'string'],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
        ]);

        if (isset($data['password'])) {
            if (! Hash::check($data['current_password'], $user->password)) {
                return response()->json(['message' => 'Mot de passe actuel incorrect.'], 422);
            }
        }

        unset($data['current_password']);

        // role_id n'est pas assignable : un utilisateur ne peut pas changer son role ici
        $user->update($data);

        return response()->json($user->fresh()->load('role'));
    }
}