<?php
declare(strict_types=1);

namespace App\Actions\Spent;

use App\Models\Spent;

class StoreSpentAction
{
    public function execute(array $data, int $userId): Spent
    {
        return Spent::create([
            'expense_date' => $data['expense_date'],
            'name'         => trim($data['spentName']),
            'price'        => trim($data['price']),
            'user_id'      => $userId,
        ]);
    }
}
