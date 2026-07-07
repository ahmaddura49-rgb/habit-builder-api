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
