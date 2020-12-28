<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
}
