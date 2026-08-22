<?php
declare(strict_types=1);

namespace App\Actions\Budget;

use App\Enums\MovementType;
use App\Models\Budget;
use App\Models\Movement;
use Carbon\Carbon;

class GetBudgetStatusAction
{
    public function execute(Budget $budget): array
    {
        $start = Carbon::parse($budget->month)->startOfMonth();
        $end = Carbon::parse($budget->month)->endOfMonth();

        $spent = (int) Movement::where('user_id', $budget->user_id)
            ->where('category_id', $budget->category_id)
            ->where('type', MovementType::Expense)
            ->whereBetween('movement_date', [$start->toDateString(), $end->toDateString()])
            ->sum('amount');

        $percentageUsed = $budget->amount > 0
            ? round(($spent / $budget->amount) * 100, 1)
            : 0.0;

        $level = match (true) {
            $percentageUsed >= 100 => 'over',
            $percentageUsed >= 80 => 'warning',
            $percentageUsed >= 50 => 'notice',
            default => 'ok',
        };

        return [
            'spent' => $spent,
            'percentageUsed' => $percentageUsed,
            'level' => $level,
        ];
    }
}
