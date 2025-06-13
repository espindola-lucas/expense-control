<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sell extends Model
{
    use HasFactory;

    protected $fillable = ['sell_date', 'name', 'price', 'user_id',];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
