<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\RecurringPayment\DeleteRecurringPaymentAction;
use App\Actions\RecurringPayment\GetRecurringPaymentsAction;
use App\Actions\RecurringPayment\StoreRecurringPaymentAction;
use App\Actions\RecurringPayment\UpdateRecurringPaymentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecurringPayment\StoreRecurringPaymentRequest;
use App\Http\Requests\RecurringPayment\UpdateRecurringPaymentRequest;
use App\Http\Resources\RecurringPaymentResource;
use App\Models\RecurringPayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecurringPaymentApiController extends Controller
{
    public function index(Request $request, GetRecurringPaymentsAction $action): JsonResponse
    {
        $recurringPayments = $action->execute($request->user()->id);

        return response()->json(RecurringPaymentResource::collection($recurringPayments));
    }

    public function store(StoreRecurringPaymentRequest $request, StoreRecurringPaymentAction $action): JsonResponse
    {
        $recurringPayment = $action->execute($request->validated(), $request->user()->id);

        return response()->json(new RecurringPaymentResource($recurringPayment->fresh(['account', 'category'])), 201);
    }

    public function update(
        UpdateRecurringPaymentRequest $request,
        RecurringPayment $recurringPayment,
        UpdateRecurringPaymentAction $action,
    ): JsonResponse {
        $this->authorizeOwnership($request, $recurringPayment);

        $action->execute($recurringPayment, $request->validated());

        return response()->json(new RecurringPaymentResource($recurringPayment->fresh(['account', 'category'])));
    }

    public function destroy(Request $request, RecurringPayment $recurringPayment, DeleteRecurringPaymentAction $action): JsonResponse
    {
        $this->authorizeOwnership($request, $recurringPayment);

        $action->execute($recurringPayment);

        return response()->json(null, 204);
    }

    private function authorizeOwnership(Request $request, RecurringPayment $recurringPayment): void
    {
        if ($recurringPayment->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
