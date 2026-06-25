<?php
declare(strict_types=1);

namespace App\Actions\Spent;

use App\Models\Spent;

class UpdateSpentAction
{
    public function execute(Spent $spent, array $data): void
    {
        $spent->update($data);
    }
}
