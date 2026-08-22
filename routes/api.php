<?php

use App\Http\Controllers\Api\AccountApiController;
use App\Http\Controllers\Api\BudgetApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\PersonalConfigurationApiController;
use App\Http\Controllers\Api\RecurringPaymentApiController;
use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\Api\SpentApiController;
use App\Http\Controllers\Api\TagApiController;
use App\Http\Controllers\Api\UserSettingApiController;
use App\Http\Controllers\SessionAuthController;
use App\Http\Middleware\SyncRecurringPayments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [SessionAuthController::class, 'login']);

Route::middleware(['auth:sanctum', SyncRecurringPayments::class])->group(function () {
    Route::post('/logout', [SessionAuthController::class, 'logout']);
    Route::get('/user', fn(Request $request) => $request->user());
    Route::apiResource('spents', SpentApiController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('api.spents');
    Route::apiResource('personal-configurations', PersonalConfigurationApiController::class)
        ->only(['index', 'store', 'update', 'destroy']);
    Route::apiResource('accounts', AccountApiController::class)
        ->only(['index', 'store', 'update', 'destroy']);
    Route::apiResource('categories', CategoryApiController::class)
        ->only(['index', 'store', 'update', 'destroy']);
    Route::apiResource('tags', TagApiController::class)
        ->only(['index', 'store', 'update', 'destroy']);
    Route::apiResource('budgets', BudgetApiController::class)
        ->only(['index', 'store', 'update', 'destroy']);
    Route::apiResource('recurring-payments', RecurringPaymentApiController::class)
        ->only(['index', 'store', 'update', 'destroy']);
    Route::prefix('reports')->group(function () {
        Route::get('summary', [ReportApiController::class, 'summary']);
        Route::get('timeseries', [ReportApiController::class, 'timeseries']);
        Route::get('by-category', [ReportApiController::class, 'byCategory']);
    });
    Route::get('/user-settings', [UserSettingApiController::class, 'show']);
    Route::put('/user-settings', [UserSettingApiController::class, 'update']);
});
