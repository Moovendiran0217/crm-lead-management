<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $user = \App\Models\User::where(
            'email',
            $credentials['email']
        )->first();

        if (
            !$user ||
            !Hash::check($credentials['password'], $user->password)
        ) {
            return response()->json([
                'message' => 'Invalid email or password.',
            ], 401);
        }

        if (!$user->isActive()) {
            return response()->json([
                'message' => 'Your account is inactive.',
            ], 403);
        }

        $token = $user->createToken('crm-api')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()
            ->currentAccessToken()
            ?->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }
}
