<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Models\HabitTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HabitTemplateController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $savedTemplateIds = $user->savedHabitTemplates()
            ->pluck('habit_templates.id')
            ->toArray();

        $templates = HabitTemplate::where('is_active', true)
            ->when(request('category'), function ($query) {
                $query->where('category', request('category'));
            })
            ->orderBy('category')
            ->orderBy('title')
            ->get()
            ->map(function ($template) use ($savedTemplateIds) {
                $template->is_saved = in_array($template->id, $savedTemplateIds);

                return $template;
            });

        return ApiResponse::success(
            $templates,
            'Habit templates fetched successfully',
            200
        );
    }





    public function start(string $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $template = HabitTemplate::where('is_active', true)
            ->findOrFail($id);

        $alreadyStarted = $user->habits()
            ->where('title', $template->title)
            ->exists();

        if ($alreadyStarted) {
            return ApiResponse::error(
                'You already started this habit.',
                422
            );
        }

        $habit = Habit::create([
            'user_id' => $user->id,
            'title' => $template->title,
            'description' => $template->description,
            'frequency' => $template->frequency,
            'is_active' => true,
        ]);

        return ApiResponse::success(
            $habit,
            'Habit started successfully',
            201
        );
    }

    public function save(string $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $template = HabitTemplate::where('is_active', true)
            ->findOrFail($id);

        $alreadySaved = $user->savedHabitTemplates()
            ->where('habit_template_id', $template->id)
            ->exists();

        if ($alreadySaved) {
            return ApiResponse::error(
                'This habit template is already saved.',
                422
            );
        }

        $user->savedHabitTemplates()->attach($template->id);

        return ApiResponse::success(
            null,
            'Habit template saved successfully',
            200
        );
    }




    public function unsave(string $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $template = HabitTemplate::findOrFail($id);

        $user->savedHabitTemplates()->detach($template->id);

        return ApiResponse::success(
            null,
            'Habit template removed from saved habits',
            200
        );
    }




    public function saved()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $templates = $user->savedHabitTemplates()
            ->where('is_active', true)
            ->orderBy('category')
            ->orderBy('title')
            ->get();

        return ApiResponse::success(
            $templates,
            'Saved habit templates fetched successfully',
            200
        );
    }
}
