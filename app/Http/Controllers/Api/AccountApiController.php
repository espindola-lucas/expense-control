<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Account\DeleteAccountAction;
use App\Actions\Account\GetAccountsAction;
use App\Actions\Account\StoreAccountAction;
use App\Actions\Account\UpdateAccountAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\StoreAccountRequest;
use App\Http\Requests\Account\UpdateAccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountApiController extends Controller
{
    public function index(Request $request, GetAccountsAction $action): JsonResponse
    {
        $accounts = $action->execute($request->user()->id);

        return response()->json(AccountResource::collection($accounts));
    }

    public function store(StoreAccountRequest $request, StoreAccountAction $action): JsonResponse
    {
        $account = $action->execute($request->validated(), $request->user()->id);

        return response()->json(new AccountResource($account), 201);
    }

    public function update(UpdateAccountRequest $request, Account $account, UpdateAccountAction $action): JsonResponse
    {
        $this->authorizeOwnership($request, $account);

        $action->execute($account, $request->validated());

        return response()->json(new AccountResource($account->fresh()));
    }

    public function destroy(Request $request, Account $account, DeleteAccountAction $action): JsonResponse
    {
        $this->authorizeOwnership($request, $account);

        $action->execute($account);

        return response()->json(null, 204);
    }

    private function authorizeOwnership(Request $request, Account $account): void
    {
        if ($account->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
