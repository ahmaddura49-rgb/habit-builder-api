<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class HabitTemplate extends Model
{
    protected $fillable = [
        'title',
        'description',
        'frequency',
        'category',
        'is_active',
    ];





    public function savedByUsers()
    {
        return $this->belongsToMany(
            User::class,
            'saved_habit_templates'
        )->withTimestamps();
    }
}
