<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\HabitCategory;
use App\Models\Habit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function habitCategories()
    {
        return $this->hasMany(HabitCategory::class);
    }

    public function habits()
    {
        return $this->hasMany(Habit::class);
    }

    public function followUps()
    {
        return $this->hasMany(HabitFollowUp::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Habit[]
     */
    public function getHabits()
    {
        return $this->habits()->with('category')->with('followUps')->get();
    }

    public function getHabitsWithFollow($date)
    {
        $dateToFilter = Carbon::parse($date);
        return $this->habits()->with('followUps')->get();
    }
}
