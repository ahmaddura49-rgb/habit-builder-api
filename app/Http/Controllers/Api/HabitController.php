<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHabitRequest;
use App\Http\Requests\UpdateHabitRequest;
use App\Http\Resources\HabitResource;
use App\Models\Habit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiResponse;
use Carbon\Carbon;

class HabitController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $habits = $user->habits()
            ->when(request('search'), function ($query) {
                $query->where('title', 'like', '%' . request('search') . '%');
                // WHERE title LIKE '%gym%'
            })
            ->latest()
            ->paginate(10);

        return ApiResponse::success(
            HabitResource::collection($habits),
            'Habits fetched successfully',
            200,
            [
                'current_page' => $habits->currentPage(),
                'last_page' => $habits->lastPage(),
                'per_page' => $habits->perPage(),
                'total' => $habits->total(),
            ]
        );
    }


    public function summary()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $habits = $user->habits()
            ->with([
                'logs' => function ($query) {
                    $query->orderBy('completed_date', 'desc');
                }
            ])
            ->latest()
            ->get();

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $last7Days = collect();

        for ($i = 0; $i < 7; $i++) {
            $last7Days->push(
                $today->copy()->subDays($i)->toDateString()
            );
        }

        $data = $habits->map(function ($habit) use ($today, $yesterday, $last7Days) {

            $dates = $habit->logs
                ->pluck('completed_date')
                ->map(function ($date) {
                    return Carbon::parse($date)->toDateString();
                })
                ->toArray();

            $completedToday = in_array(
                $today->toDateString(),
                $dates
            );

            $weeklyCompletions = collect($dates)
                ->filter(function ($date) use ($last7Days) {
                    return $last7Days->contains($date);
                })
                ->count();

            // Current streak
            $streak = 0;

            if (!empty($dates)) {

                $firstDate = Carbon::parse($dates[0]);

                if ($firstDate->isSameDay($today)) {
                    $currentDate = $today->copy();
                } elseif ($firstDate->isSameDay($yesterday)) {
                    $currentDate = $yesterday->copy();
                } else {
                    $currentDate = null;
                }

                if ($currentDate) {

                    foreach ($dates as $date) {

                        if ($date === $currentDate->toDateString()) {
                            $streak++;
                            $currentDate->subDay();
                        } else {
                            break;
                        }
                    }
                }
            }

            // Best streak
            $bestStreak = 0;
            $currentBestRun = 0;
            $previousDate = null;

            $ascendingDates = collect($dates)
                ->unique()
                ->sort()
                ->values();

            foreach ($ascendingDates as $date) {

                $currentDate = Carbon::parse($date);

                if ($previousDate) {

                    $difference = $previousDate->diffInDays($currentDate);

                    if ((int) $difference === 1) {
                        $currentBestRun++;
                    } else {
                        $currentBestRun = 1;
                    }
                } else {
                    $currentBestRun = 1;
                }

                if ($currentBestRun > $bestStreak) {
                    $bestStreak = $currentBestRun;
                }

                $previousDate = $currentDate;
            }

            return [
                'id' => $habit->id,
                'title' => $habit->title,
                'description' => $habit->description,
                'frequency' => $habit->frequency,
                'is_active' => $habit->is_active,

                'streak' => $streak,
                'completed_today' => $completedToday,
                'total_completions' => $habit->logs->count(),
                'weekly_completions' => $weeklyCompletions,
                'best_streak' => $bestStreak,
            ];
        });

        return ApiResponse::success(
            $data,
            'Habit summary fetched successfully',
            200
        );
    }


    public function store(StoreHabitRequest $request)
    {
        $user_id = Auth::user()->id; #we can writ Auth::id();
        $validatedData = $request->validated();
        $validatedData['user_id'] = $user_id;
        $habit = Habit::create($validatedData);

        return ApiResponse::success(
            new HabitResource($habit),
            'Habit created successfully',
            201
        );
    }


    public function show(string $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $habit = $user->habits()->findOrFail($id);

        return ApiResponse::success(
            new HabitResource($habit),
            'Habit fetched successfully',
            200
        );
    }


    public function update(UpdateHabitRequest $request, string $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $habit = $user->habits()->findOrFail($id);
        $validatedData = $request->validated();
        $habit->update($validatedData);

        return ApiResponse::success(
            new HabitResource($habit),
            'Habit updated successfully',
            200
        );
    }


    public function destroy(string $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $habit = $user->habits()->findOrFail($id);
        $habit->delete();

        return ApiResponse::success(
            null,
            'Habit deleted successfully',
            200
        );
    }
}




// api resource
// multi
// HabitResource::collection($habits)
// one
// new HabitResource($habit)
//query building
//
