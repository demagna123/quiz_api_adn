<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|unique:users',
                'name' => 'required',
            ]);

            User::create($request->all());
            return response()->json([
                "message" => "User cree avec succès",
            ], 200);
        } catch (\Throwable $th) {
            return $th;
            return response()->json([
                "message" => "Error lors de l'inscription",
            ], 400);
        }
    }


    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            $user = User::where('email', $request->email)->first();

            if ($user) {
                Auth::login($user);

                return response()->json([
                    'exists' => true,
                    'message' => "Utilisateur connecté avec succès",
                    // 'token' => $user->createToken('api_token')->plainTextToken // si tu veux ajouter l’auth
                ], 200);
            } else {
                return response()->json([
                    'exists' => false,
                    'message' => "Email non inscrit",
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
