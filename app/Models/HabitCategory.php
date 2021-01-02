<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Habit;

class HabitCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'icon'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function habits()
    {
        return $this->hasMany(Habit::class);
    }
}
