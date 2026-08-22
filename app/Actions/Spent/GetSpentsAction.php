<?php
declare(strict_types=1);

namespace App\Actions\Spent;

use App\Models\Movement;
use Illuminate\Support\Collection;

class GetSpentsAction
{
    public function execute(
        int $userId,
        ?string $search = null,
        ?string $startDate = null,
        ?string $endDate = null,
    ): Collection {
        $query = Movement::expenses()
                      ->where('user_id', $userId)
                      ->orderBy('movement_date', 'desc');

        if ($search) {
            $query->where('name', 'ilike', '%' . $search . '%');
        } elseif ($startDate && $endDate) {
            $query->whereBetween('movement_date', [$startDate, $endDate]);
        }

        return $query->get();
    }
}
