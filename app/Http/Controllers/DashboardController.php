<?php

namespace App\Http\Controllers;

use App\Actions\Spent\GetDashboardDataAction;
use Illuminate\Http\Request;
use App\Http\Controllers\SpentController;

class DashboardController extends Controller
{
    public function index(Request $request, GetDashboardDataAction $action){
        $spentContoller = app(SpentController::class);
        return $spentContoller->index($request, $action);
    }
}
