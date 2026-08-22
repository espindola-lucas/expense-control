<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Report\GetReportCategoryBreakdownAction;
use App\Actions\Report\GetReportSummaryAction;
use App\Actions\Report\GetReportTimeseriesAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\ReportFilterRequest;
use Illuminate\Http\JsonResponse;

class ReportApiController extends Controller
{
    public function summary(ReportFilterRequest $request, GetReportSummaryAction $action): JsonResponse
    {
        $data = $action->execute(
            $request->user()->id,
            $request->validated('start_date'),
            $request->validated('end_date'),
            $request->validated('account_id'),
        );

        return response()->json($data);
    }

    public function timeseries(ReportFilterRequest $request, GetReportTimeseriesAction $action): JsonResponse
    {
        $data = $action->execute(
            $request->user()->id,
            $request->validated('start_date'),
            $request->validated('end_date'),
            $request->validated('group_by') ?? 'day',
            $request->validated('account_id'),
        );

        return response()->json($data);
    }

    public function byCategory(ReportFilterRequest $request, GetReportCategoryBreakdownAction $action): JsonResponse
    {
        $data = $action->execute(
            $request->user()->id,
            $request->validated('start_date'),
            $request->validated('end_date'),
            $request->validated('type') ?? 'expense',
            $request->validated('account_id'),
        );

        return response()->json($data);
    }
}
