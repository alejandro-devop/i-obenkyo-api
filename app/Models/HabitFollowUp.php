<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HabitFollowUp extends Model
{
    protected $fillable = [
        'apply_date',
        'story',
    ];
    use HasFactory;
}
