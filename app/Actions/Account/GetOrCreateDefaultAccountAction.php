<?php
declare(strict_types=1);

namespace App\Actions\Account;

use App\Enums\AccountType;
use App\Models\Account;

class GetOrCreateDefaultAccountAction
{
    public function execute(int $userId): Account
    {
        return Account::firstOrCreate(
            ['user_id' => $userId, 'name' => 'Efectivo'],
            ['type' => AccountType::Cash],
        );
    }
}
