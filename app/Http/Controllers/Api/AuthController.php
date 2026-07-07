<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);

        return ApiResponse::success(
            $user,
            'User registered successfully',
            201
        );
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::where('email', $validatedData['email'])->first();

        if (! $user || ! Hash::check($validatedData['password'], $user->password)) {
            return ApiResponse::error(
                'Invalid credentials',
                401
            );
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success(
            [
                'user' => $user,
                'token' => $token,
            ],
            'Logged in successfully',
            200
        );
    }

    public function logout()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->currentAccessToken()->delete();

        return ApiResponse::success(
            null,
            'Logged out successfully',
            200
        );
    }
}
