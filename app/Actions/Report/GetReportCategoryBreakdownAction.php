<?php
declare(strict_types=1);

namespace App\Actions\Report;

use App\Enums\MovementType;
use App\Models\Movement;

class GetReportCategoryBreakdownAction
{
    public function execute(
        int $userId,
        string $startDate,
        string $endDate,
        string $type = 'expense',
        ?int $accountId = null,
    ): array {
        $movementType = MovementType::from($type);

        $query = Movement::where('movements.user_id', $userId)
            ->where('movements.type', $movementType)
            ->whereBetween('movements.movement_date', [$startDate, $endDate]);

        if ($accountId) {
            $query->where('movements.account_id', $accountId);
        }

        $rows = $query->join('categories', 'categories.id', '=', 'movements.category_id')
            ->selectRaw('categories.id as category_id, categories.name as category_name, categories.icon as category_icon, SUM(movements.amount) as total')
            ->groupBy('categories.id', 'categories.name', 'categories.icon')
            ->orderByDesc('total')
            ->get();

        $grandTotal = (int) $rows->sum('total');

        return $rows->map(fn ($row) => [
            'category_id' => $row->category_id,
            'category_name' => $row->category_name,
            'category_icon' => $row->category_icon,
            'total' => (int) $row->total,
            'percentage' => $grandTotal > 0 ? round(($row->total / $grandTotal) * 100, 1) : 0.0,
        ])->all();
    }
}
