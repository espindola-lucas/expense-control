<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $fillable = ['start_counting', 'end_counting', 'filter', 'available_money', 'month_available_money', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
