<?php
declare(strict_types=1);

namespace App\Actions\Category;

use App\Models\Category;

class DeleteCategoryAction
{
    public function execute(Category $category): void
    {
        if ($category->is_default) {
            abort(422, 'No se puede eliminar la categoría por defecto.');
        }

        $category->delete();
    }
}
