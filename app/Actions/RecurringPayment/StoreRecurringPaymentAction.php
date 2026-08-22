<?php
declare(strict_types=1);

namespace App\Actions\RecurringPayment;

use App\Models\RecurringPayment;

class StoreRecurringPaymentAction
{
    public function execute(array $data, int $userId): RecurringPayment
    {
        return RecurringPayment::create([
            'user_id' => $userId,
            'account_id' => $data['account_id'],
            'category_id' => $data['category_id'] ?? null,
            'name' => $data['name'],
            'type' => $data['type'],
            'amount' => $data['amount'],
            'frequency' => $data['frequency'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? null,
            'is_active' => true,
        ]);
    }
}
