<?php
declare(strict_types=1);

namespace App\Actions\RecurringPayment;

use App\Models\RecurringPayment;

class DeleteRecurringPaymentAction
{
    public function execute(RecurringPayment $recurringPayment): void
    {
        $recurringPayment->delete();
    }
}
