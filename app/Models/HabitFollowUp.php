<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HabitFollowUp
 * @package App\Models
 * @property numeric id
 * @property numeric habit_id
 * @property boolean accomplished
 * @property string apply_date
 * @property boolean is_counter
 * @property numeric counter
 * @property numeric counter_goal
 * @property string story
 */
class HabitFollowUp extends Model
{
    protected $fillable = [
        'accomplished',
        'apply_date',
        'is_counter',
        'counter',
        'counter_goal',
        'story',
    ];
    use HasFactory;

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }
}
