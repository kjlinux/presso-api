<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'phone_number' => 'required|string',
                'country_code' => 'required|string',
                'pin_code' => 'required|string|min:4|max:4',
            ]);

            $user = User::where('phone_number', $request->phone_number)
                ->where('country_code', $request->country_code)
                ->first();

            if (!$user || !$user->checkPin($request->pin_code)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Numéro de téléphone ou code PIN incorrect'
                ], 401);
            }

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'success' => true,
                'message' => 'Connexion réussie',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'pressing_name' => $user->pressing_name,
                        'phone_number' => $user->full_phone_number,
                        'country_name' => $user->country_name,
                    ],
                    'token' => $token,
                    'token_type' => 'bearer',
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données de connexion invalides',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la connexion'
            ], 500);
        }
    }
}
