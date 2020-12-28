<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\HabitFollowUp;

class Habit extends Model
{
    protected $fillable = [
        'title', 'description', 'start', 'goal_date', 'streak_count', 'streak_goal', 'category_id'
    ];
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function followUps()
    {
        return $this->hasMany(HabitFollowUp::class);
    }

    public function getFollowUps()
    {
        return $this->followUps()->orderBy('apply_date', 'ASC')->get();
    }
}
