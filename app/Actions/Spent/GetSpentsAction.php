<?php
declare(strict_types=1);

namespace App\Actions\Spent;

use App\Models\Spent;
use Illuminate\Support\Collection;

class GetSpentsAction
{
    public function execute(
        int $userId,
        ?string $search = null,
        ?string $startDate = null,
        ?string $endDate = null,
    ): Collection {
        $query = Spent::where('user_id', $userId)
                      ->orderBy('expense_date', 'desc');

        if ($search) {
            $query->where('name', 'ilike', '%' . $search . '%');
        } elseif ($startDate && $endDate) {
            $query->whereBetween('expense_date', [$startDate, $endDate]);
        }

        return $query->get();
    }
}
