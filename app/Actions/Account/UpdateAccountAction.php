<?php
declare(strict_types=1);

namespace App\Actions\Account;

use App\Models\Account;

class UpdateAccountAction
{
    public function execute(Account $account, array $data): void
    {
        $account->update($data);
    }
}
