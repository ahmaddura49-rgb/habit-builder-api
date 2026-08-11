<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HabitLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\ApiResponse;

class HabitLogController extends Controller
{
    public function index(string $habitId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $habit = $user->habits()->findOrFail($habitId);

        $logs = $habit->logs()
            ->orderBy('completed_date', 'desc')
            ->paginate(10);

        return ApiResponse::success(
            $logs->items(),
            'Logs fetched successfully',
            200,
            [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ]
        );
    }




    public function completeToday(string $habitId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $habit = $user->habits()->findOrFail($habitId);

        $today = now()->toDateString();

        $alreadyCompleted = HabitLog::where('habit_id', $habit->id)
            ->where('completed_date', $today)
            ->exists();

        if ($alreadyCompleted) {
            return ApiResponse::error(
                'This habit has already been completed today.',
                422
            );
        }

        $habitLog = HabitLog::create([
            'habit_id' => $habit->id,
            'user_id' => $user->id,
            'completed_date' => $today,
        ]);

        return ApiResponse::success(
            $habitLog,
            'Habit marked as completed for today successfully',
            201
        );
    }









    public function streak(string $habitId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $habit = $user->habits()->findOrFail($habitId);

        $logs = $habit->logs()
            ->orderBy('completed_date', 'desc')
            ->pluck('completed_date')
            ->toArray();

        if (empty($logs)) {
            return ApiResponse::success(
                [
                    'habit_id' => $habit->id,
                    'streak' => 0,
                ],
                'Streak fetched successfully',
                200
            );
        }

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $firstLogDate = Carbon::parse($logs[0]);

        if ($firstLogDate->isSameDay($today)) {
            $currentDate = $today->toDateString();
        } elseif ($firstLogDate->isSameDay($yesterday)) {
            $currentDate = $yesterday->toDateString();
        } else {
            return ApiResponse::success(
                [
                    'habit_id' => $habit->id,
                    'streak' => 0,
                ],
                'Streak fetched successfully',
                200
            );
        }

        $streak = 0;

        foreach ($logs as $date) {
            if ($date === $currentDate) {
                $streak++;
                $currentDate = Carbon::parse($currentDate)->subDay()->toDateString();
            } else {
                break;
            }
        }

        return ApiResponse::success(
            [
                'habit_id' => $habit->id,
                'streak' => $streak,
            ],
            'Streak fetched successfully',
            200
        );
    }
}
