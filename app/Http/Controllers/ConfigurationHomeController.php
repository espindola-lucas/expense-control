<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Helpers\Helps;
use App\Http\Controllers\PersonalConfigurationController;

class ConfigurationHomeController extends Controller
{
    public function index(){
        $months = Helps::getNameMonths();
        $user = Auth::user();
        $hasPersonalConfiguration = false;

        $getPersonalData = PersonalConfigurationController::getPersonalData();

        if ($getPersonalData->isNotEmpty()){
            $hasPersonalConfiguration = true;
        }

        $currentYear = now()->year;
        $footerInformation = [
            'year' => $currentYear,
            'textInformation' => 'Expense Control'
        ];

        return view('configuration', [
            'months' => $months,
            'personalConfig' => $getPersonalData,
            'user' => $user,
            'hasPersonalConfiguration' => $hasPersonalConfiguration,
            'footerInformation' => $footerInformation,
            'branchName' => Helps::getGitBranchName()
        ]);
    }
}
