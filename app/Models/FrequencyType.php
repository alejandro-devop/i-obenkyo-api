<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * Class FrequencyType
 * @package App\Models
 * @param string name
 * @param numeric days
 * @param numeric id
 * @param boolean is_daily
 * @param boolean is_weekly
 * @param boolean is_monthly
 * @param boolean is_every_year
 */
class FrequencyType extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'days', 'is_daily', 'is_weekly', 'is_monthly', 'is_every_year'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
