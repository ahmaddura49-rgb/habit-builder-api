<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\HabitController;
use App\Http\Controllers\Api\HabitLogController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    // Route::post('/habits/{habitId}/logs', [HabitLogController::class, 'store']);
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
