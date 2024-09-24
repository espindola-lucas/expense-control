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
        // show default settings
        $lastConfiguration = Configuration::latest()->first();
        
        $current_date = Carbon::now();
        $current_month = $current_date->month;
        $selectedMonth = $request->input('month_available_money', $current_month);
        $isDefaultMonth = true;
        $idLastConfiguration = null;

        if($lastConfiguration == null){
            $config = [
                'available_money' => null,
                'month_available_money' => $selectedMonth,
                'start_counting' => null,
                'end_counting' => null,
                'expense_percentage_limit' => null
            ];
        }else{
            $endCounting = Carbon::parse($lastConfiguration['end_counting']);
            // Verificar si la fecha actual es después de la fecha de fin de conteo
            if ($current_date->greaterThan($endCounting)) {
                // Si se ha pasado la fecha de fin, resetear para nueva configuración
                $isDefaultMonth = true;
                $config = [
                    'available_money' => null,
                    'month_available_money' => $selectedMonth,
                    'start_counting' => null,
                    'end_counting' => null,
                    'expense_percentage_limit' => null
                ];
            } else {
                // Usar la configuración existente
                $config = [
                    'available_money' => $lastConfiguration['available_money'],
                    'month_available_money' => $lastConfiguration['month_available_money'],
                    'start_counting' => $lastConfiguration['start_counting'],
                    'end_counting' => $lastConfiguration['end_counting'],
                    'expense_percentage_limit' => $lastConfiguration['expense_percentage_limit']
                ];
                $idLastConfiguration = $lastConfiguration['id'];
                $selectedMonth = $lastConfiguration['month_available_money'];
                $isDefaultMonth = false;
            }
        }

        $currentYear = now()->year;
        $footerInformation = [
            'year' => $currentYear,
            'textInformation' => 'Expense Control'
        ];

        return view('configuration', [
            'months' => $months,
            'config' => $config,
            'selectedMonth' => $selectedMonth,
            'isDefaultMonth' => $isDefaultMonth,
            'idLastConfiguration' => $idLastConfiguration,
            'footerInformation' => $footerInformation
        ]);
    }

    public function create(){
        return view('configuration');
    }

    public function store(Request $request){
        // dd($request->all());
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

    public function show(){

    }

    public function edit(Configuration $configuration){
        $months = \Helps::getNameMonths();
        $isDefaultMonth = true;
        $selectedMonth = $configuration['month_available_money'];
        $currentYear = now()->year;
        $footerInformation = [
            'year' => $currentYear,
            'textInformation' => 'Expense Control'
        ];

        return view('configuration.edit-configuration', [
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
}
