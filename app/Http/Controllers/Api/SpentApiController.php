<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Spent\DeleteSpentAction;
use App\Actions\Spent\GetDashboardDataAction;
use App\Actions\Spent\GetSpentsAction;
use App\Actions\Spent\StoreSpentAction;
use App\Actions\Spent\UpdateSpentAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\SpentResource;
use App\Models\Spent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpentApiController extends Controller
{
    public function index(
        Request $request,
        GetSpentsAction $spentsAction,
        GetDashboardDataAction $dashboardAction,
    ): JsonResponse {
        $userId = $request->user()->id;
        $search = $request->query('search');

        $dashboard = $dashboardAction->execute(
            userId:         $userId,
            type:           $request->query('type', 'personal'),
            selectedMonth:  $request->query('period'),
            filterText:     $search,
            startDateParam: $request->query('start_date'),
            endDateParam:   $request->query('end_date'),
        );

        $spents = $spentsAction->execute(
            userId:    $userId,
            search:    $search,
            startDate: $search ? null : $dashboard['startDate'],
            endDate:   $search ? null : $dashboard['endDate'],
        );

        return response()->json([
            'spents'           => SpentResource::collection($spents),
            'monthly_balance'  => $dashboard['monthly_balance'] ?? null,
            'percentageUsed'   => $dashboard['percentageUsed'] ?? null,
            'hasConfiguration' => $dashboard['hasConfiguration'],
        ]);
    }

    public function store(Request $request, StoreSpentAction $action): JsonResponse
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date'   => 'required|date',
        ]);

        $spent = $action->execute([
            'expense_date' => $validated['date'],
            'spentName'    => $validated['name'],
            'price'        => $validated['amount'],
        ], $request->user()->id);

        return response()->json(new SpentResource($spent), 201);
    }

    public function update(Request $request, Spent $spent, UpdateSpentAction $action): JsonResponse
    {
        if ($spent->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date'   => 'required|date',
        ]);

        $action->execute($spent, [
            'expense_date' => $validated['date'],
            'name'         => $validated['name'],
            'price'        => $validated['amount'],
        ]);

        return response()->json(new SpentResource($spent->fresh()));
    }

    public function destroy(Request $request, Spent $spent, DeleteSpentAction $action): JsonResponse
    {
        if ($spent->user_id !== $request->user()->id) {
            abort(403);
        }

        $action->execute($spent);

        return response()->json(null, 204);
    }
}
