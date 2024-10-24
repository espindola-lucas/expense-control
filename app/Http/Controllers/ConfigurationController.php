<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ConfigurationController extends Controller
{
    public function index(Request $request){
        $months = \Helps::getNameMonths();
        $user = Auth::user();
        $allConfigurations = $this->getAllConfiguration($user->id);
        
        foreach ($allConfigurations as $configuration) {
            $configuration->available_money = $this->formatMoney($configuration->available_money);
            $month_name = \Helps::getMonthNameByKey($configuration->month_available_money);
            $configuration->month_available_money = "{$configuration->month_available_money} - {$month_name}";
        }

        $currentYear = now()->year;
        $footerInformation = [
            'year' => $currentYear,
            'textInformation' => 'Expense Control'
        ];
        return view('configuration', [
            'months' => $months,
            'configurations' => $allConfigurations,
            'user' => $user,
            'footerInformation' => $footerInformation
        ]);
    }

    public function create(){
        $months = \Helps::getNameMonths();
        $user = Auth::user();

        return view('configuration.create-configuration', [
            'months' => $months,
            'user' => $user
        ]);
    }

    public function store(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'available_money' => 'required',
                'filter' => 'required',
                'month_available_money' => 'required',
                'expense_percentage_limit' => 'required'
            ]);

            $filter = $request->input('filter');
            $start_counting = $request->input('start_counting') ?: null;
            $end_counting = $request->input('end_counting') ?: null;

            Configuration::create([
                'start_counting' => $start_counting,
                'end_counting' => $end_counting,
                'filter' => $filter,
                'available_money' => $request->input('available_money'),
                'month_available_money' => $request->input('month_available_money'),
                'expense_percentage_limit' => $request->input('expense_percentage_limit'),
                'user_id' => Auth::user()->id
            ]);

            return Redirect::back()->with('success', 'Configuración guardada exitosamente.');
        }
    }

    public function show($id){
        $configId = Configuration::find($id);
        $isDefaultMonth = false;
        $user = Auth::user();
        $configuration = $this->getAllConfiguration($user->id);
        $selectedMonth = $configuration->first()->month_available_money ?? null;
        $months = \Helps::getNameMonths();
        return view('configuration.show-configuration', [
            'user' => $user,
            'configuration' => $configId,
            'isDefaultMonth' => $isDefaultMonth,
            'selectedMonth' => $selectedMonth,
            'months' => $months
        ]);
    }

    public function edit(Configuration $configuration){
        $months = \Helps::getNameMonths();
        $user = Auth::user();
        $selectedMonth = $configuration['month_available_money'];
        $currentYear = now()->year;
        $footerInformation = [
            'year' => $currentYear,
            'textInformation' => 'Expense Control'
        ];
        
        $isDefaultMonth = true;
        
        return view('configuration.edit-configuration', [
            'user' => $user,
            'configuration' => $configuration,
            'months' => $months,
            'isDefaultMonth' => $isDefaultMonth,
            'selectedMonth' => $selectedMonth,
            'footerInformation' => $footerInformation
        ]);
    }

    public function update(Request $request, Configuration $configuration){
        $input = $request->all();
        $configuration->update($input);
        return redirect('configuration');
    }

    public function destroy(){

    }

    private function getAllConfiguration($userId){
        $configurations = Configuration::where('user_id', $userId)
                                        ->get();
        
        foreach($configurations as $configuration){
            $configuration->start_counting = Carbon::parse($configuration->start_counting)->format('d/m/Y');
            $configuration->end_counting = Carbon::parse($configuration->end_counting)->format('d/m/Y');
        }
        
        return $configurations;
    }

    private function formatMoney($amount) {
        if (is_numeric($amount)) {
            return number_format($amount, 0, ',', '.');
        }
        return $amount;
    }
}