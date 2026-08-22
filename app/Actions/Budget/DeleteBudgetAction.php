<?php
declare(strict_types=1);

namespace App\Actions\Budget;

use App\Models\Budget;

class DeleteBudgetAction
{
    public function execute(Budget $budget): void
    {
        $budget->delete();
    }
}
