<?php

namespace App\Models;

use App\Enums\AccountType;
use App\Enums\MovementType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'type', 'initial_balance', 'is_archived', 'position',
    ];

    protected $appends = ['balance'];

    protected function casts(): array
    {
        return [
            'type' => AccountType::class,
            'is_archived' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }

    public function incomingTransfers()
    {
        return $this->hasMany(Movement::class, 'destination_account_id');
    }

    public function getBalanceAttribute(): int
    {
        $income = $this->movements()->where('type', MovementType::Income)->sum('amount');
        $expense = $this->movements()->where('type', MovementType::Expense)->sum('amount');
        $transfersOut = $this->movements()->where('type', MovementType::Transfer)->sum('amount');
        $transfersIn = $this->incomingTransfers()->where('type', MovementType::Transfer)->sum('amount');

        return $this->initial_balance + $income - $expense - $transfersOut + $transfersIn;
    }
}
