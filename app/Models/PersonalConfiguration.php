<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalConfiguration extends Model
{
    use HasFactory;

    protected $fillable = ['start_counting', 'end_counting', 'available_money', 'month_available_money', 'expense_percentage_limit', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}