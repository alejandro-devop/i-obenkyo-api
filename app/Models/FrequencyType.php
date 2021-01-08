<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class FrequencyType extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'days', 'is_daily', 'is_weekly', 'is_monthly', 'is_every_year'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
