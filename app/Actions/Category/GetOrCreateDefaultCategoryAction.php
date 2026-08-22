<?php
declare(strict_types=1);

namespace App\Actions\Category;

use App\Enums\CategoryType;
use App\Models\Category;

class GetOrCreateDefaultCategoryAction
{
    public function execute(int $userId, CategoryType $type): Category
    {
        return Category::firstOrCreate(
            ['user_id' => $userId, 'name' => 'Sin categoría', 'type' => $type],
            ['is_default' => true],
        );
    }
}
