<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Category\DeleteCategoryAction;
use App\Actions\Category\GetCategoriesAction;
use App\Actions\Category\StoreCategoryAction;
use App\Actions\Category\UpdateCategoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
    public function index(Request $request, GetCategoriesAction $action): JsonResponse
    {
        $categories = $action->execute($request->user()->id);

        return response()->json(CategoryResource::collection($categories));
    }

    public function store(StoreCategoryRequest $request, StoreCategoryAction $action): JsonResponse
    {
        $category = $action->execute($request->validated(), $request->user()->id);

        return response()->json(new CategoryResource($category), 201);
    }

    public function update(UpdateCategoryRequest $request, Category $category, UpdateCategoryAction $action): JsonResponse
    {
        $this->authorizeOwnership($request, $category);

        $action->execute($category, $request->validated());

        return response()->json(new CategoryResource($category->fresh()));
    }

    public function destroy(Request $request, Category $category, DeleteCategoryAction $action): JsonResponse
    {
        $this->authorizeOwnership($request, $category);

        $action->execute($category);

        return response()->json(null, 204);
    }

    private function authorizeOwnership(Request $request, Category $category): void
    {
        if ($category->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
