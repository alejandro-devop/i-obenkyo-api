<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string text
 * @property string description
 * @property boolean is_done
 * @property boolean is_all_day
 * @property Carbon apply_date
 */
class Task extends Model
{
    use HasFactory;
    protected $fillable = ['text', 'description', 'apply_date', 'is_done', 'is_all_day'];

    public function group()
    {
        return $this->belongsTo(TaskGroup::class);
    }
}
