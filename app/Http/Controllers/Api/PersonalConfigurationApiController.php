<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\PersonalConfiguration\DeletePersonalConfigurationAction;
use App\Actions\PersonalConfiguration\GetPersonalConfigurationsAction;
use App\Actions\PersonalConfiguration\StorePersonalConfigurationAction;
use App\Actions\PersonalConfiguration\UpdatePersonalConfigurationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PersonalConfiguration\StorePersonalConfigurationRequest;
use App\Http\Requests\PersonalConfiguration\UpdatePersonalConfigurationRequest;
use App\Http\Resources\PersonalConfigurationResource;
use App\Models\PersonalConfiguration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PersonalConfigurationApiController extends Controller
{
    public function index(Request $request, GetPersonalConfigurationsAction $action): JsonResponse
    {
        $configurations = $action->execute($request->user()->id);

        return response()->json(PersonalConfigurationResource::collection($configurations));
    }

    public function store(StorePersonalConfigurationRequest $request, StorePersonalConfigurationAction $action): JsonResponse
    {
        $configuration = $action->execute($request->validated(), $request->user()->id);

        return response()->json(new PersonalConfigurationResource($configuration), 201);
    }

    public function update(
        UpdatePersonalConfigurationRequest $request,
        PersonalConfiguration $personalConfiguration,
        UpdatePersonalConfigurationAction $action,
    ): JsonResponse {
        $this->authorizeOwnership($request, $personalConfiguration);

        $action->execute($personalConfiguration, $request->validated());

        return response()->json(new PersonalConfigurationResource($personalConfiguration->fresh()));
    }

    public function destroy(
        Request $request,
        PersonalConfiguration $personalConfiguration,
        DeletePersonalConfigurationAction $action,
    ): JsonResponse {
        $this->authorizeOwnership($request, $personalConfiguration);

        $action->execute($personalConfiguration);

        return response()->json(null, 204);
    }

    private function authorizeOwnership(Request $request, PersonalConfiguration $configuration): void
    {
        if ($configuration->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
