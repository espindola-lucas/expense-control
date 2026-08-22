<?php
declare(strict_types=1);

namespace App\Actions\Report;

use App\Enums\MovementType;
use App\Models\Movement;

class GetReportSummaryAction
{
    public function execute(int $userId, string $startDate, string $endDate, ?int $accountId = null): array
    {
        $query = Movement::where('user_id', $userId)
            ->whereBetween('movement_date', [$startDate, $endDate]);

        if ($accountId) {
            $query->where('account_id', $accountId);
        }

        $income = (int) (clone $query)->where('type', MovementType::Income)->sum('amount');
        $expense = (int) (clone $query)->where('type', MovementType::Expense)->sum('amount');
        $count = (clone $query)->count();

        return [
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
            'count' => $count,
        ];
    }
}
