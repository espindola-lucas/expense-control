<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\UserSetting\GetOrCreateUserSettingAction;
use App\Actions\UserSetting\UpdateUserSettingAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserSetting\UpdateUserSettingRequest;
use App\Http\Resources\UserSettingResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserSettingApiController extends Controller
{
    public function show(Request $request, GetOrCreateUserSettingAction $action): JsonResponse
    {
        $setting = $action->execute($request->user()->id);

        return response()->json(new UserSettingResource($setting));
    }

    public function update(
        UpdateUserSettingRequest $request,
        GetOrCreateUserSettingAction $getOrCreateAction,
        UpdateUserSettingAction $updateAction,
    ): JsonResponse {
        $setting = $getOrCreateAction->execute($request->user()->id);

        $updateAction->execute($setting, $request->validated());

        return response()->json(new UserSettingResource($setting->fresh()));
    }
}
