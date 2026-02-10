<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spent;
use App\Models\Sell;
use App\Models\PersonalConfiguration;
use App\Http\Controllers\PersonalConfigurationController;
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
        $user = Auth::user();

        $selectedMonth = $request->input('period');

        $getAllPeriods = Helps::getAllPeriods($user->id, 'personal');

        $startDate = $request->input('start_date') ?? Helps::getStartDateFromDatabase($user->id, 'personal');
        $endDate = $request->input('end_date') ?? Helps::getEndDateFromDatabase($user->id, 'personal');

        // Get sells directly for the selected period
        $sells = Helps::getFilteredSellsByPeriod($user->id, $startDate, $endDate);

        $currentDate = Helps::getdate();

        return view('dashboard', [
            'sells' => $sells,
            'user' => $user,
            'allPeriods' => $getAllPeriods,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentDate' => $currentDate,
            'branchName' => Helps::getGitBranchName(),
            'hasConfiguration' => $hasConfiguration,
            'type' => 'personal',
            'hasBothConfig' => false,
            'onlyFilter' => false
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        // This controller handles sales (sells) only now
        return view('abm.create', [
            'user' => $user,
            'type' => 'personal',
            'isSell' => true,
            'storeRoute' => 'sells.store',
            'dateField' => 'sell_date',
            'nameField' => 'sellName',
            'labelDate' => 'Dia de la venta',
            'labelName' => 'Nombre de la venta',
            'today' => now()->format('Y-m-d'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
            return redirect()->route('dashboard')
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
        
        return redirect()->route('dashboard');
    }
}
