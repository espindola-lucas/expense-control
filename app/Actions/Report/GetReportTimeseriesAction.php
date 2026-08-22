<?php
declare(strict_types=1);

namespace App\Actions\Report;

use App\Enums\MovementType;
use App\Models\Movement;
use Carbon\Carbon;

class GetReportTimeseriesAction
{
    public function execute(
        int $userId,
        string $startDate,
        string $endDate,
        string $groupBy = 'day',
        ?int $accountId = null,
    ): array {
        $truncUnit = $groupBy === 'month' ? 'month' : 'day';

        $query = Movement::where('user_id', $userId)
            ->whereBetween('movement_date', [$startDate, $endDate]);

        if ($accountId) {
            $query->where('account_id', $accountId);
        }

        $rows = $query
            ->selectRaw("date_trunc('{$truncUnit}', movement_date) as period")
            ->selectRaw('SUM(CASE WHEN type = ? THEN amount ELSE 0 END) as income', [MovementType::Income->value])
            ->selectRaw('SUM(CASE WHEN type = ? THEN amount ELSE 0 END) as expense', [MovementType::Expense->value])
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return $rows->map(fn ($row) => [
            'period' => Carbon::parse($row->period)->toDateString(),
            'income' => (int) $row->income,
            'expense' => (int) $row->expense,
        ])->all();
    }
}
