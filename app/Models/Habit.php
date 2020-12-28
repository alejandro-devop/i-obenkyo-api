<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\HabitFollowUp;

/**
 * Class Habit
 * @package App\Models
 * @property string title           A title for the habit
 * @property string description     A text which describes why to track the habit
 * @property string start           The date the habit track starts
 * @property string goal_date       The date when the habit should be finished
 * @property numeric streak_count   Streak counter
 * @property numeric streak_goal    Streak to achieve
 * @property numeric category_id    The category assigned to the habit
 * @property numeric counter_goal   The count you want to achieve
 * @property boolean is_counter     If the habit is accomplished when an a counter is complete
 * @property boolean should_avoid   If is an habit to avoid
 * @property boolean should_keep    If is an habit to keep
 * @property boolean max_streak     Counter with the max streak
 */
class Habit extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start',
        'goal_date',
        'streak_count',
        'streak_goal',
        'category_id',
        'counter_goal',
        'is_counter',
        'should_avoid',
        'should_keep',
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
