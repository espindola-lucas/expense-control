<?php

namespace App\Models;

use App\Enums\MovementType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'account_id', 'destination_account_id', 'category_id', 'recurring_payment_id',
        'type', 'name', 'amount', 'movement_date',
    ];

    protected function casts(): array
    {
        return [
            'type' => MovementType::class,
            'movement_date' => 'date',
            'amount' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function destinationAccount()
    {
        return $this->belongsTo(Account::class, 'destination_account_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function recurringPayment()
    {
        return $this->belongsTo(RecurringPayment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopeExpenses(Builder $query): Builder
    {
        return $query->where('type', MovementType::Expense);
    }

    public function scopeIncomes(Builder $query): Builder
    {
        return $query->where('type', MovementType::Income);
    }
}
