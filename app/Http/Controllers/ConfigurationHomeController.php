<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Helpers\Helps;
use App\Http\Controllers\PersonalConfigurationController;
use App\Http\Controllers\BusinessConfigurationController;

class ConfigurationHomeController extends Controller
{
    public function index(){
        $months = Helps::getNameMonths();
        $user = Auth::user();
        $hasPersonalConfiguration = false;
        $hasBusinessConfiguration = false;

        $getPersonalData = PersonalConfigurationController::getPersonalData();
        $getBusinessData = BusinessConfigurationController::getBusinessData();

        if ($getPersonalData->isNotEmpty()){
            $hasPersonalConfiguration = true;
        }

        if ($getBusinessData->isNotEmpty()){
            $hasBusinessConfiguration = true;
        }

        $currentYear = now()->year;
        $footerInformation = [
            'year' => $currentYear,
            'textInformation' => 'Expense Control'
        ];

        return view('configuration', [
            'months' => $months,
            'personalConfig' => $getPersonalData,
            'businessConfig' => $getBusinessData,
            'user' => $user,
            'hasPersonalConfiguration' => $hasPersonalConfiguration,
            'hasBusinessConfiguration' => $hasBusinessConfiguration,
            'footerInformation' => $footerInformation
        ]);
    }
}
