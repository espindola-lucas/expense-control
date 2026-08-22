<?php
declare(strict_types=1);

namespace App\Actions\Category;

use App\Models\Category;
use Illuminate\Support\Collection;

class GetCategoriesAction
{
    public function execute(int $userId): Collection
    {
        return Category::where('user_id', $userId)
            ->orderBy('type')
            ->orderBy('position')
            ->orderBy('id')
            ->get();
    }
}
