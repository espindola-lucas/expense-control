<?php
declare(strict_types=1);

namespace App\Actions\Budget;

use App\Models\Budget;

class UpdateBudgetAction
{
    public function execute(Budget $budget, array $data): void
    {
        $budget->update($data);
    }
}
