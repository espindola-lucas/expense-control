<?php
declare(strict_types=1);

namespace App\Actions\Account;

use App\Models\Account;
use Illuminate\Support\Collection;

class GetAccountsAction
{
    public function execute(int $userId): Collection
    {
        return Account::where('user_id', $userId)
            ->orderBy('position')
            ->orderBy('id')
            ->get();
    }
}
