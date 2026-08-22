<?php
declare(strict_types=1);

namespace App\Actions\Account;

use App\Models\Account;

class StoreAccountAction
{
    public function execute(array $data, int $userId): Account
    {
        return Account::create([
            'user_id'         => $userId,
            'name'            => $data['name'],
            'type'            => $data['type'],
            'initial_balance' => $data['initial_balance'] ?? 0,
        ]);
    }
}
