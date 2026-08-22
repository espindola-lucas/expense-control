<?php
declare(strict_types=1);

namespace App\Actions\Category;

use App\Models\Category;

class StoreCategoryAction
{
    public function execute(array $data, int $userId): Category
    {
        return Category::create([
            'user_id' => $userId,
            'name'    => $data['name'],
            'type'    => $data['type'],
            'icon'    => $data['icon'] ?? null,
        ]);
    }
}
