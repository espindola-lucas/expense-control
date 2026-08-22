<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Budget\DeleteBudgetAction;
use App\Actions\Budget\GetBudgetsAction;
use App\Actions\Budget\StoreBudgetAction;
use App\Actions\Budget\UpdateBudgetAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Budget\StoreBudgetRequest;
use App\Http\Requests\Budget\UpdateBudgetRequest;
use App\Http\Resources\BudgetResource;
use App\Models\Budget;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BudgetApiController extends Controller
{
    public function index(Request $request, GetBudgetsAction $action): JsonResponse
    {
        $budgets = $action->execute($request->user()->id, $request->query('month'));

        return response()->json(BudgetResource::collection($budgets));
    }

    public function store(StoreBudgetRequest $request, StoreBudgetAction $action): JsonResponse
    {
        $budget = $action->execute($request->validated(), $request->user()->id);

        return response()->json(new BudgetResource($budget->fresh('category')), 201);
    }

    public function update(UpdateBudgetRequest $request, Budget $budget, UpdateBudgetAction $action): JsonResponse
    {
        $this->authorizeOwnership($request, $budget);

        $action->execute($budget, $request->validated());

        return response()->json(new BudgetResource($budget->fresh('category')));
    }

    public function destroy(Request $request, Budget $budget, DeleteBudgetAction $action): JsonResponse
    {
        $this->authorizeOwnership($request, $budget);

        $action->execute($budget);

        return response()->json(null, 204);
    }

    private function authorizeOwnership(Request $request, Budget $budget): void
    {
        if ($budget->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
