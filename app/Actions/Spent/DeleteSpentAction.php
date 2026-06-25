<?php
declare(strict_types=1);

namespace App\Actions\Spent;

use App\Models\Spent;

class DeleteSpentAction
{
    public function execute(Spent $spent): void
    {
        $spent->delete();
    }
}
