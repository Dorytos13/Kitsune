<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Inscription d'un nouvel utilisateur
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws \Exception
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Connexion d'un utilisateur
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws \Exception
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'Les identifiants fournis sont incorrects.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    /**
     * Déconnexion d'un utilisateur
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     * @throws ValidationException
     */
    public function logout(Request $request): JsonResponse
    {
        if (!$request->user()) {
            return response()->json([
                'error' => 'Aucun utilisateur connecté.'
            ], 401);
        }
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnexion réussie'], 200);
    }

    
    
    /**
     * Récupérer le profil de l'utilisateur connecté
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     * @throws ValidationException
     */
    public function profile(Request $request): JsonResponse
    {
        if (!$request->user()) {
            return response()->json([
                'error' => 'Utilisateur non authentifié.'
            ], 401); 
        }

        return response()->json($request->user(), 200);
    }
}