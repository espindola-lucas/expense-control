<?php
declare(strict_types=1);

namespace App\Actions\RecurringPayment;

use App\Models\RecurringPayment;
use Illuminate\Support\Collection;

class GetRecurringPaymentsAction
{
    public function execute(int $userId): Collection
    {
        return RecurringPayment::where('user_id', $userId)
            ->with(['account', 'category'])
            ->orderBy('name')
            ->get();
    }
}
