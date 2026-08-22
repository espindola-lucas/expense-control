<?php
declare(strict_types=1);

namespace App\Actions\Budget;

use App\Models\Budget;

class StoreBudgetAction
{
    public function execute(array $data, int $userId): Budget
    {
        return Budget::create([
            'user_id' => $userId,
            'category_id' => $data['category_id'],
            'month' => $data['month'],
            'amount' => $data['amount'],
        ]);
    }
}
