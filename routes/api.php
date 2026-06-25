<?php

use App\Http\Controllers\Api\PersonalConfigurationApiController;
use App\Http\Controllers\Api\SpentApiController;
use App\Http\Controllers\SessionAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [SessionAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [SessionAuthController::class, 'logout']);
    Route::get('/user', fn(Request $request) => $request->user());
    Route::apiResource('spents', SpentApiController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('api.spents');
    Route::apiResource('personal-configurations', PersonalConfigurationApiController::class)
        ->only(['index', 'store', 'update', 'destroy']);
});
