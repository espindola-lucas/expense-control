<?php
declare(strict_types=1);

namespace App\Actions\Spent;

use App\Models\Movement;

class DeleteSpentAction
{
    public function execute(Movement $spent): void
    {
        $spent->delete();
    }
}
