<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class ConfigurationController extends Controller
{
    public function index(Request $request){
        $months = \Helps::getNameMonths();

        // show default settings
        $lastConfiguration = Configuration::latest()->first();

        $current_month = now()->month;
        $selectedMonth = $request->input('month_available_money', $current_month);
        $isDefaultMonth = true;

        if($lastConfiguration == null){
            $config = array(
                'available_money' => null,
                'month_available_money' => $selectedMonth,
                'start_counting' => null,
                'end_counting' => null,
            );
        }else{
            $config = array(
                'available_money' => $lastConfiguration['available_money'],
                'month_available_money' => $lastConfiguration['month_available_money'],
                'start_counting' => $lastConfiguration['start_counting'],
                'end_counting' => $lastConfiguration['end_counting']
            );
            $idLastConfiguration = $lastConfiguration['id'];
            $selectedMonth = $lastConfiguration['month_available_money'];
            $isDefaultMonth = false;
        }

        return view('configuration', [
            'months' => $months,
            'config' => $config,
            'selectedMonth' => $selectedMonth,
            'isDefaultMonth' => $isDefaultMonth,
            'idLastConfiguration' => $idLastConfiguration
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
            $start_counting = $request->input('start_counting') ?: null;
            $end_counting = $request->input('end_counting') ?: null;

            Configuration::create([
                'start_counting' => $start_counting,
                'end_counting' => $end_counting,
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

    public function edit(Configuration $configuration){
        $months = \Helps::getNameMonths();
        $isDefaultMonth = true;
        $selectedMonth = $configuration['month_available_money'];

        return view('configuration.edit-configuration', [
            'configuration' => $configuration,
            'months' => $months,
            'isDefaultMonth' => $isDefaultMonth,
            'selectedMonth' => $selectedMonth
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
