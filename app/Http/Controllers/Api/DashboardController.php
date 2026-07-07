<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HabitLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponse;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        $totalHabits = $user->habits()->count();

        $activeHabits = $user->habits()
            ->where('is_active', true)
            ->count();

        $completedToday = HabitLog::where('user_id', $user->id)
            ->count();


        $totalCompletions = HabitLog::where('user_id', $user->id)
            ->count();

        return ApiResponse::success(
            [
                'total_habits' => $totalHabits,
                'active_habits' => $activeHabits,
                'completed_today' => $completedToday,
                'total_completions' => $totalCompletions,
            ],
            'Dashboard statistics fetched successfully',
            200
        );
    }
}
