<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Bill
 * @package App\Models
 * @property string name
 * @property numeric id
 * @property string description
 * @property int frequency_id
 * @property int user_id
 * @property Carbon apply_date
 */
class Bill extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'apply_date', 'apply_day', 'custom_days', 'frequency_id', 'user_id', 'value'];

    public function frequency()
    {
        return $this->belongsTo(FrequencyType::class);
    }

    public function getFrequency()
    {
        return $this->frequency()->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
