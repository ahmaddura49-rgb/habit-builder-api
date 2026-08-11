<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\HabitController;
use App\Http\Controllers\Api\HabitLogController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\PasswordResetController;
use App\Models\User;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');









Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});






Route::middleware('auth:sanctum')->group(function () {
    Route::post('/habits', [HabitController::class, 'store']);
    Route::get('/habits', [HabitController::class, 'index']);
    route::get('/habits/{id}', [HabitController::class, 'show']);
    Route::put('/habits/{id}', [HabitController::class, 'update']);
    Route::delete('/habits/{id}', [HabitController::class, 'destroy']);
});




Route::middleware('auth:sanctum')->group(function () {
    Route::get('/habits/{habitId}/logs', [HabitLogController::class, 'index']);
    Route::post('/habits/{habitId}/complete-today', [HabitLogController::class, 'completeToday']);
    Route::get('/habits/{habitId}/streak', [HabitLogController::class, 'streak']);
});




Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});





Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/change-password', [ProfileController::class, 'changePassword']);
});




Route::get('/email/verify/{id}/{hash}', function (Request $request, string $id, string $hash) {

    $user = User::findOrFail($id);

    if (! hash_equals(
        (string) $hash,
        sha1($user->getEmailForVerification())
    )) {
        return ApiResponse::error(
            'Invalid verification link',
            403
        );
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return ApiResponse::success(
        null,
        'Email verified successfully',
        200
    );
})->middleware('signed')->name('verification.verify');




Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);

Route::get('/reset-password/{token}', function (string $token) {
    return ApiResponse::success(
        [
            'token' => $token,
            'email' => request('email'),
        ],
        'Password reset token received successfully.',
        200
    );
})->name('password.reset');








Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
