<?php
declare(strict_types=1);

namespace App\Actions\Budget;

use App\Models\Budget;
use Illuminate\Support\Collection;

class GetBudgetsAction
{
    public function execute(int $userId, ?string $month = null): Collection
    {
        $query = Budget::where('user_id', $userId)->with('category');

        if ($month) {
            $query->whereDate('month', $month);
        }

        return $query->orderBy('month', 'desc')->get();
    }
}
