<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'frequency',
        'is_active',
    ];



    public function user()
    {
        return $this->belongsTo(user::class);
    }




    public function logs()
    {
        return $this->hasMany(HabitLog::class);
    }
}
