<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SpentController;
use App\Http\Controllers\SellController;

class DashboardController extends Controller
{
    public function index(Request $request){
        $type = $request->query('type', 'personal');

        if($type === 'business'){
            $sellController = app(SellController::class);
            return $sellController->index($request);
        }

        $spentContoller = app(SpentController::class);
        return $spentContoller->index($request);
    }
}
