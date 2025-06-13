<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessConfiguration extends Model
{
    use HasFactory;

    protected $fillable = ['start_counting', 'end_counting', 'amount_sold', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
