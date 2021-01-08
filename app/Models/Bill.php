<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Bill
 * @package App\Models
 * @property string name
 * @property string description
 * @property Carbon apply_date
 */
class Bill extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'apply_date'];
}
