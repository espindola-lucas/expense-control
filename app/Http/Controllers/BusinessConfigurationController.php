<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\Helps;
use App\Models\BusinessConfiguration;
use Illuminate\Support\Facades\Auth;

class BusinessConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    static function getBusinessData(){
        $user = Auth::user();
        $configs = Helps::getAllConfiguration($user->id, 'business');

        foreach ($configs as $configuration) {
            $configuration->available_money = Helps::formatValue($configuration->available_money);

            $currentDate = Carbon::now();
            $endDate = Carbon::createFromFormat('d/m/Y', $configuration->end_counting);
            $configuration->show_edit_button = $currentDate->lte($endDate);
        }

        return $configs;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $months = Helps::getNameMonths();
        $user = Auth::user();

        return view('configuration.create-business-configuration', [
            'months' => $months,
            'user' => $user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'start_counting' => 'required|date',
                'end_counting'  => 'required|date|after_or_equal:start_counting',
            ]);
    
            $start_counting = $request->input('start_counting');
            $end_counting = $request->input('end_counting');
    
            BusinessConfiguration::create([
                'start_counting' => $start_counting,
                'end_counting' => $end_counting,
                'amount_sold' => 0,
                'user_id' => Auth::user()->id
            ]);

            return redirect()->route('configuration.index')->with('success', 'Configuartion guardada con exito');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $configId = BusinessConfiguration::findOrFail($id);
        $user = Auth::user();
        $configuration = Helps::getAllConfiguration($user->id, 'business');
        $months = Helps::getNameMonths();

        return view('configuration.show-business-configuration', [
            'user' => $user,
            'configuration' => $configId,
            'months' => $months
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $configuration = BusinessConfiguration::findOrfail($id);
        $months = Helps::getNameMonths();
        $user = Auth::user();
        $currentYear = now()->year;
        $footerInformation = [
            'year' => $currentYear,
            'textInformation' => 'Expense Control'
        ];
        
        return view('configuration.edit-business-configuration', [
            'user' => $user,
            'configuration' => $configuration,
            'months' => $months,
            'footerInformation' => $footerInformation
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BusinessConfiguration $configuration)
    {
        $input = $request->all();
        $configuration->update($input);
        return redirect('configuration');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $config = BusinessConfiguration::findOrFail($id);
        $config->delete();

        return redirect('configuration');
    }
}
