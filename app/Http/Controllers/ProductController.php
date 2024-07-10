<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Configuration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request){
        // get month and year current
        $currentYear = now()->year;
        $currentMonth = now()->month;

        // filter
        $query = Product::query();
        $year_filter = $request->input('year', $currentYear);
        $month_filter = $request->input('month', $currentMonth);
        if ($year_filter) {
            $query->whereYear('expense_date', $year_filter);
        }

        if ($month_filter) {
            $query->whereMonth('expense_date', $month_filter);
        }
        $products = $query->get();
        $user = Auth::user();

        $configuration_money = Configuration::where('user_id', Auth::user()->id)
                                            ->where('month_available_money', $month_filter)
                                            ->first();

        $available_money = $configuration_money ? $configuration_money->available_money : 0;

        $query = Product::query();

        if ($year_filter){
            $query->whereRaw('EXTRACT(YEAR FROM "expense_date") = ?', [$year_filter]);
        }

        if ($month_filter){
            $query->whereRaw('EXTRACT(MONTH FROM "expense_date") = ?', [$month_filter]);
        }

        $totalPrice = $query->sum('price');

        $years = range(2024, 2030);

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

        return view('dashboard', [
            'products' => $products,
            'user' => $user->name,
            'totalPrice' => $totalPrice,
            'years' => $years,
            'months' => $months,
            'selectedYear' => $year_filter,
            'selectedMonth' => $month_filter,
            'available_money' => $available_money
        ]);
    }

    public function create(){
        return view('products.create-product');
    }

    public function store(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'expense_date' => 'required',
                'productName' => 'required',
                'price' => 'required',
            ]);

            Product::create([
                'expense_date' => $request->input('expense_date'),
                'name' => $request->input('productName'),
                'price' => $request->input('price'),
                'user_id' => Auth::user()->id
            ]);

            return redirect()->route('dashboard')->with('success', 'Producto agregado exitosamente.');
        }
        return view('products.create-product');
    }

    public function edit(Product $product){
        return view('products.edit-product', [
            'product' => $product
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $input = $request->all();
        $product -> update($input);
        return redirect('dashboard');
    }

    public function destroy(Request $request, Product $product){
        $product->delete();
        $year_filter = $request->input('year');
        $month_filter = $request->input('month');

        return redirect()->route('dashboard', ['year' => $year_filter, 'month' => $month_filter]);
    }
}
