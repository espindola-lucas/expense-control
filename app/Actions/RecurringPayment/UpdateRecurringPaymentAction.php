<?php
declare(strict_types=1);

namespace App\Actions\RecurringPayment;

use App\Models\RecurringPayment;

class UpdateRecurringPaymentAction
{
    public function execute(RecurringPayment $recurringPayment, array $data): void
    {
        $recurringPayment->update($data);
    }
}
