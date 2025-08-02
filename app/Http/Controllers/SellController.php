<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spent;
use App\Models\Sell;
use App\Models\PersonalConfiguration;
use App\Http\Controllers\PersonalConfigurationController;
use App\Http\Controllers\BusinessConfigurationController;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\Helps;

class SellController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $hasConfiguration = true;
        $hasBothConfig = false;
        $user = Auth::user();
        $config = Helps::getAllConfiguration($user->id);

        $type = $request->query('type');
        $selectedMonth = $request->input('period');

        $getAllPeriods = Helps::getAllPeriods($user->id, 'business');

        $startDate = $request->input('start_date') ?? Helps::getStartDateFromDatabase($user->id, 'business');
        $endDate = $request->input('end_date') ?? Helps::getEndDateFromDatabase($user->id, 'business');

        $getPersonalData = PersonalConfigurationController::getPersonalData();
        $getBusinessData = BusinessConfigurationController::getBusinessData();

        if($getPersonalData->isNotEmpty() && $getBusinessData->isNotEmpty()){
            $hasBothConfig = true;
        }

        $data = Helps::filterByPeriod($user->id, $startDate, $endDate, $type);

        $currentDate = Helps::getdate();

        return view('dashboard', [
            'sells' => $data['sells'],
            'user' => $user,
            'allPeriods' => $getAllPeriods,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentDate' => $currentDate,
            'branchName' => Helps::getGitBranchName(),
            'hasConfiguration' => $hasConfiguration,
            'type' => $type,
            'hasBothConfig' => $hasBothConfig,
            'onlyFilter' => false
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        $type = $request->query('type', 'personal');
        $isSell = $type === 'business';

        return view('abm.create', [
            'user' => $user,
            'type' => $type,
            'isSell' => $isSell,
            'storeRoute' => $isSell ? 'sells.store' : 'spents.store',
            'dateField' => $isSell ? 'sell_date' : 'expense_date',
            'nameField' => $isSell ? 'sellName' : 'spentName',
            'labelDate' => $isSell ? 'Dia de la venta' : 'Dia de la compra',
            'labelName' => $isSell ? 'Nombre de la venta' : 'Nombre del gasto',
            'today' => now()->format('Y-m-d'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $type = $request->query('type', 'business');

        if($request->isMethod('post')){
            $request->validate([
                'sell_date' => 'required',
                'sellName' => 'required',
                'price' => 'required'
            ]);

            $sellName = trim($request->input('sellName'));
            $price = trim($request->input('price'));

            Sell::create([
                'sell_date' => $request->input('sell_date'),
                'name' => $sellName,
                'price' => $price,
                'user_id' => Auth::user()->id
            ]);

            return redirect()->route('dashboard', ['type' => $type])
                            ->with('success', 'Venta agregada correctamente.');
        }
        return view('abm.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Sell $sell)
    {
        $sell->delete();
        
        return redirect()->route('dashboard', ['type' => 'business']);
    }
}
