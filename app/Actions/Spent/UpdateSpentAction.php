<?php
declare(strict_types=1);

namespace App\Actions\Spent;

use App\Models\Movement;

class UpdateSpentAction
{
    public function execute(Movement $spent, array $data): void
    {
        $spent->update([
            'name'          => trim($data['name']),
            'amount'        => $data['price'],
            'movement_date' => $data['expense_date'],
        ]);
    }
}
