<?php

namespace Database\Seeders;

use App\Models\HabitTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HabitTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            // Health
            [
                'title' => 'Drink 2L of Water',
                'description' => 'Drink at least 2 liters of water throughout the day.',
                'frequency' => 'daily',
                'category' => 'Health',
                'is_active' => true,
            ],
            [
                'title' => 'Sleep 8 Hours',
                'description' => 'Get at least 8 hours of sleep.',
                'frequency' => 'daily',
                'category' => 'Health',
                'is_active' => true,
            ],
            [
                'title' => 'Eat a Healthy Breakfast',
                'description' => 'Start your day with a healthy and balanced breakfast.',
                'frequency' => 'daily',
                'category' => 'Health',
                'is_active' => true,
            ],
            [
                'title' => 'Take Vitamins',
                'description' => 'Remember to take your daily vitamins.',
                'frequency' => 'daily',
                'category' => 'Health',
                'is_active' => true,
            ],

            // Fitness
            [
                'title' => 'Walk 30 Minutes',
                'description' => 'Walk for at least 30 minutes.',
                'frequency' => 'daily',
                'category' => 'Fitness',
                'is_active' => true,
            ],
            [
                'title' => 'Exercise 30 Minutes',
                'description' => 'Complete at least 30 minutes of physical exercise.',
                'frequency' => 'daily',
                'category' => 'Fitness',
                'is_active' => true,
            ],
            [
                'title' => 'Stretch for 10 Minutes',
                'description' => 'Spend 10 minutes stretching your body.',
                'frequency' => 'daily',
                'category' => 'Fitness',
                'is_active' => true,
            ],
            [
                'title' => 'Do 20 Push-ups',
                'description' => 'Complete 20 push-ups during the day.',
                'frequency' => 'daily',
                'category' => 'Fitness',
                'is_active' => true,
            ],

            // Study
            [
                'title' => 'Read for 20 Minutes',
                'description' => 'Read a book or educational material for 20 minutes.',
                'frequency' => 'daily',
                'category' => 'Study',
                'is_active' => true,
            ],
            [
                'title' => 'Study for 1 Hour',
                'description' => 'Spend one focused hour studying.',
                'frequency' => 'daily',
                'category' => 'Study',
                'is_active' => true,
            ],
            [
                'title' => 'Learn 10 New Words',
                'description' => 'Learn and review 10 new words.',
                'frequency' => 'daily',
                'category' => 'Study',
                'is_active' => true,
            ],
            [
                'title' => 'Review Today\'s Notes',
                'description' => 'Review the notes you studied today.',
                'frequency' => 'daily',
                'category' => 'Study',
                'is_active' => true,
            ],

            // Productivity
            [
                'title' => 'Plan Your Day',
                'description' => 'Write down your main tasks and priorities for the day.',
                'frequency' => 'daily',
                'category' => 'Productivity',
                'is_active' => true,
            ],
            [
                'title' => 'Complete Your Most Important Task',
                'description' => 'Finish the most important task on your list.',
                'frequency' => 'daily',
                'category' => 'Productivity',
                'is_active' => true,
            ],
            [
                'title' => 'Avoid Social Media for 1 Hour',
                'description' => 'Stay away from social media for one focused hour.',
                'frequency' => 'daily',
                'category' => 'Productivity',
                'is_active' => true,
            ],
            [
                'title' => 'Organize Your Workspace',
                'description' => 'Spend a few minutes organizing your workspace.',
                'frequency' => 'daily',
                'category' => 'Productivity',
                'is_active' => true,
            ],

            // Mindfulness
            [
                'title' => 'Meditate for 10 Minutes',
                'description' => 'Spend 10 minutes practicing meditation.',
                'frequency' => 'daily',
                'category' => 'Mindfulness',
                'is_active' => true,
            ],
            [
                'title' => 'Write 3 Things You\'re Grateful For',
                'description' => 'Write down three things you are grateful for today.',
                'frequency' => 'daily',
                'category' => 'Mindfulness',
                'is_active' => true,
            ],
            [
                'title' => 'Journal for 10 Minutes',
                'description' => 'Spend 10 minutes writing about your thoughts or your day.',
                'frequency' => 'daily',
                'category' => 'Mindfulness',
                'is_active' => true,
            ],
            [
                'title' => 'Take a 10-Minute Break Without Your Phone',
                'description' => 'Take a short break without using your phone.',
                'frequency' => 'daily',
                'category' => 'Mindfulness',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            HabitTemplate::create($template);
        }
    }
}
