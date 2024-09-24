<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_date', 'name', 'price', 'user_id',
    ];

    protected $dates = ['expense_date'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
