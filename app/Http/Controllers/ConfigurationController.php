<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ConfigurationController extends Controller
{
    public function index(Request $request){
        // dropdown for months
        $monthsTranslation = [
            'January' => 'Enero',
            'February' => 'Febrero',
            'March' => 'Marzo',
            'April' => 'Abril',
            'May' => 'Mayo',
            'June' => 'Junio',
            'July' => 'Julio',
            'August' => 'Agosto',
            'September' => 'Septiembre',
            'October' => 'Octubre',
            'November' => 'Noviembre',
            'December' => 'Diciembre'
        ];

        $months = collect([]);
        for ($month = 1; $month <= 12; $month++){
            $monthName = \DateTime::createFromFormat('!m', $month)->format('F');
            $translatedMonthName = $monthsTranslation[$monthName];
            $months->push((object)[
                'name' => $translatedMonthName,
                'value' => str_pad($month, 2, '0', STR_PAD_LEFT)
            ]);
        }

        // show default settings
        $lastConfiguration = Configuration::latest()->first();

        $current_month = now()->month;
        $selectedMonth = $request->input('month_available_money', $current_month);

        if($lastConfiguration == null){
            $config = array(
                'available_money' => 0,
                'month_available_money' => $selectedMonth
            );
        }else{
            $config = array(
                'available_money' => $lastConfiguration['available_money'],
                'month_available_money' => $lastConfiguration['month_available_money']
            );
            $selectedMonth = $lastConfiguration['month_available_money'];
        }

        return view('configuration', [
            'months' => $months,
            'config' => $config,
            'selectedMonth' => $selectedMonth
        ]);
    }

    public function create(){
        return view('configuration');
    }

    public function store(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'available_money' => 'required',
                'filter' => 'required',
                'month_available_money' => 'required'
            ]);
            
            $filter = $request->input('filter');
            Configuration::create([
                'filter' => $filter,
                'available_money' => $request->input('available_money'),
                'month_available_money' => $request->input('month_available_money'),
                'user_id' => Auth::user()->id
            ]);
            
            return Redirect::back()->with('success', 'Configuración guardada exitosamente.');
        }
    }

    public function show(){

    }

    public function edit(){

    }

    public function update(){

    }

    public function destroy(){

    }
}
