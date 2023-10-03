<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function register(RegisterUserRequest $request): JsonResponse {
        $data = $request->all();

        $user = User::create($data);

        $token = $user->createToken('token');

        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken
        ]);
    }

    public function login(LoginUserRequest $request): JsonResponse {
        $credentials = $request->all();

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $credentials['email'])->first();
            $permissions = $user->getAllPermissions();

            $abilities = $permissions->pluck('name');

            $token = $user->createToken('token', $abilities->toArray());

            return response()->json([
                'user' => $user,
                'token' => $token->plainTextToken
            ]);
        } else {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([], 200);
    }
}
