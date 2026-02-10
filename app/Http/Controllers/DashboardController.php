<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SpentController;

class DashboardController extends Controller
{
    public function index(Request $request){
        $spentContoller = app(SpentController::class);
        return $spentContoller->index($request);
    }
}
