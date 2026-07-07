<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateHabitRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiResponse;

class ProfileController extends Controller
{
    public function show()
    {
        return ApiResponse::success(
            Auth::user(),
            'Profile fetched successfully',
            200
        );
    }


    public function update(UpdateProfileRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->update($request->validated());

        return ApiResponse::success(
            $user->fresh(),
            'Profile updated successfully',
            200
        );
    }



    public function changePassword(ChangePasswordRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return ApiResponse::error(
                'Current password is incorrect.',
                422
            );
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return ApiResponse::success(
            null,
            'Password changed successfully',
            200
        );
    }
}
