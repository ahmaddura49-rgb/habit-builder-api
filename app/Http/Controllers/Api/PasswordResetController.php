<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Support\Facades\Password;

use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status !== Password::RESET_LINK_SENT) {
            return ApiResponse::error(
                'Unable to send password reset link.',
                400
            );
        }

        return ApiResponse::success(
            null,
            'Password reset link sent successfully.',
            200
        );
    }





    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return ApiResponse::error(
                'Unable to reset password.',
                400
            );
        }

        return ApiResponse::success(
            null,
            'Password reset successfully.',
            200
        );
    }
}
