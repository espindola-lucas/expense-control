<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FixedExpense;
use Carbon\Carbon;
class FixedExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $currentYear = now()->year;
        $footerInformation = [
            'year' => $currentYear,
            'textInformation' => 'Expense control'
        ];

        $data = FixedExpense::get();

        $amount_values = 0;
        foreach($data as $d){
            $amount_values += $d->value;
            $d->value = number_format($d->value, 0, '', '.');
            $d->formatted_created_at = Carbon::parse($d->created_at)->format('d/m/Y');
        }
        
        return view('fixed-expense', [
            'user' => $user,
            'footerInformation' => $footerInformation,
            'fixedexpenses' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        $currentYear = now()->year;
        $footerInformation = [
            'year' => $currentYear,
            'textInformation' => 'Expense control'
        ];

        return view('fixedexpense.create-fixed-expense', [
            'user' => $user,
            'footerInformation' => $footerInformation
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        echo Auth::user()->id;
        if($request->isMethod('post')){
            $request->validate([
                'name_fixed_expense' => 'required',
                'value_fixed_expense' => 'required'
            ]);

            FixedExpense::create([
                'input_name' => $request->input('name_fixed_expense'),
                'value' => $request->input('value_fixed_expense'),
                'user_id' => Auth::user()->id
            ]);
        }

        return redirect()->route('fixedexpenses.index')->with('success', 'Gasto fijo agregado exitosamente.');
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
    public function edit($id)
    {
        $user = Auth::user();
        $currentYear = now()->year;
        $footerInformation = [
            'year' => $currentYear,
            'textInformation' => 'Expense control'
        ];

        $fixedExpense = FixedExpense::findOrFail($id);

        return view('fixedexpense.edit-fixed-expense', [
            'fixedexpense' => $fixedExpense,
            'user' => $user,
            'footerInformation' => $footerInformation
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FixedExpense $fixedExpense)
    {
        $input = $request->all();
        $fixedExpense->update($input);
        return redirect('fixedexpenses');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $fixedExpense = FixedExpense::findOrFail($id);
        $fixedExpense->delete();
        return redirect('fixedexpenses');        
    }
}
