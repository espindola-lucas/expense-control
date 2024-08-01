<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Configuration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request) {
        $currentYear = now()->year;
        $currentMonth = now()->month;
    
        $yearFilter = $request->input('year', $currentYear);
        $monthInput = $request->input('month', $currentMonth);
        $monthFilter = $this->formatMonth($monthInput);

        $user = Auth::user();
        // Obtener las fechas de inicio y fin desde la base de datos
        $startDate = $request->input('start_date') ?? $this->getStartDateFromDatabase($user);
        $endDate = $request->input('end_date') ?? $this->getEndDateFromDatabase($user);
    
        if ($startDate && $endDate) {
            // filtrado por rango de fechas
            $products = $this->getFilteredProductsByDateRange($startDate, $endDate);
            $availableMoney = $this->getAvailableMoney($user->id, $startDate, $endDate);
            $totalPrice = $this->getTotalPriceByDateRange($startDate, $endDate);
        } else {
            // filtrado por año y mes
            $products = $this->getFilteredProductsByYearMonth($yearFilter, $monthFilter);
            $availableMoney = $this->getAvailableMoney($user->id, $monthFilter);
            $totalPrice = $this->getTotalPriceByYearMonth($yearFilter, $monthFilter);
        }

        $restMoney = $availableMoney - $totalPrice;

        // Formatear los valores para la salida
        $formattedAvailableMoney = number_format($availableMoney, 0, '', '.');
        $formattedRestMoney = number_format($restMoney, 0, '', '.');
        $formattedTotalPrice = number_format($totalPrice, 0, '', '.');
        $lastConfiguration = $this->getLastConfiguration();
    
        $years = range(2024, 2030);
        $months = $this->getMonths();
    
        return view('dashboard', [
            'products' => $products,
            'user' => $user->name,
            'totalPrice' => $formattedTotalPrice,
            'years' => $years,
            'months' => $months,
            'selectedYear' => $yearFilter,
            'selectedMonth' => $monthFilter,
            'available_money' => $formattedAvailableMoney,
            'rest_money' => $formattedRestMoney,
            'lastConfiguration' => $lastConfiguration,
            'startDate' => $startDate,
            'endDate' => $endDate,
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

    private function getStartDateFromDatabase($user) {
        // $userId = Auth::id();
        
        // Obtener la última configuración para el usuario actual
        $configuration = Configuration::where('user_id', $user->id)
                                      ->latest()
                                      ->first();
    
        return $configuration ? $configuration->start_counting : null;
    }
    
    private function getEndDateFromDatabase($user) {
        // $userId = Auth::id();
        
        // Obtener la última configuración para el usuario actual
        $configuration = Configuration::where('user_id', $user->id)
                                      ->latest()
                                      ->first();
    
        return $configuration ? $configuration->end_counting : null;
    }

    private function formatMonth($monthInput) {
        return intval($monthInput) < 10 ? str_pad($monthInput, 2, '0', STR_PAD_LEFT) : $monthInput;
    }
    
    private function getFilteredProductsByYearMonth($yearFilter, $monthFilter) {
        $query = Product::query();
    
        if ($yearFilter) {
            $query->whereYear('expense_date', $yearFilter);
        }
    
        if ($monthFilter) {
            $query->whereMonth('expense_date', $monthFilter);
        }
    
        return $query->orderBy('expense_date')->get();
    }
    
    private function getFilteredProductsByDateRange($startDate, $endDate){
        return Product::whereBetween('expense_date', [$startDate, $endDate])
                        ->orderBy('expense_date')
                        ->get();
    }

    private function getAvailableMoney($userId, $startDate = null, $endDate = null) {
        $query = Configuration::where('user_id', $userId);

        if ($startDate && $endDate) {
            // Filtra las configuraciones donde el rango de fechas proporcionado intersecta con el rango de la configuración.
            $query->where(function ($q) use ($startDate, $endDate) {
                $q->where(function ($query) use ($startDate, $endDate) {
                    $query->whereNotNull('start_counting')
                          ->whereNotNull('end_counting')
                          ->where(function ($query) use ($startDate, $endDate) {
                              $query->whereBetween('start_counting', [$startDate, $endDate])
                                    ->orWhereBetween('end_counting', [$startDate, $endDate])
                                    ->orWhere(function ($query) use ($startDate, $endDate) {
                                        $query->where('start_counting', '<=', $startDate)
                                              ->where('end_counting', '>=', $endDate);
                                    });
                          });
                });
            });
        } else {
            // Si no se proporcionan fechas, devuelve el dinero disponible para la configuración actual.
            $query->whereNotNull('start_counting')
                  ->whereNotNull('end_counting')
                  ->whereDate('start_counting', '<=', now())
                  ->whereDate('end_counting', '>=', now())
                  ->orderBy('start_counting', 'desc');
        }

        $configurationMoney = $query->first();

        return $configurationMoney ? $configurationMoney->available_money : 0;
    }

    private function getTotalPriceByYearMonth($yearFilter, $monthFilter) {
        $query = Product::query();
    
        if ($yearFilter) {
            $query->whereRaw('EXTRACT(YEAR FROM "expense_date") = ?', [$yearFilter]);
        }
    
        if ($monthFilter) {
            $query->whereRaw('EXTRACT(MONTH FROM "expense_date") = ?', [$monthFilter]);
        }
    
        return $query->sum('price');
    }

    private function getTotalPriceByDateRange($startDate, $endDate) {
        return Product::whereBetween('expense_date', [$startDate, $endDate])
                      ->sum('price');
    }    
    
    private function formatAvailableMoney($availableMoney) {
        return number_format($availableMoney, 0, '', '.');
    }
    
    private function getLastConfiguration() {
        $lastConfiguration = Configuration::latest()->first();
    
        if ($lastConfiguration) {
            $this->formatConfigurationDates($lastConfiguration);
        }
    
        return $lastConfiguration;
    }
    
    private function formatConfigurationDates(&$configuration) {
        if (!is_null($configuration['start_counting']) && !is_null($configuration['end_counting'])) {
            $configuration['start_counting'] = $this->formatDate($configuration['start_counting']);
            $configuration['end_counting'] = $this->formatDate($configuration['end_counting']);
        } elseif (!is_null($configuration['start_counting'])) {
            $configuration['start_counting'] = $this->formatDate($configuration['start_counting']);
        } else {
            $configuration['end_counting'] = $this->formatDate($configuration['end_counting']);
        }
    }
    
    private function formatDate($date) {
        $date = str_replace('-', '/', $date);
        return \Carbon\Carbon::createFromFormat('Y/m/d', $date)->format('d/m/y');
    }
    
    private function getMonths() {
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
        for ($month = 1; $month <= 12; $month++) {
            $monthName = \DateTime::createFromFormat('!m', $month)->format('F');
            $translatedMonthName = $monthsTranslation[$monthName];
            $months->push((object)[
                'name' => $translatedMonthName,
                'value' => str_pad($month, 2, '0', STR_PAD_LEFT)
            ]);
        }
    
        return $months;
    }
    
}
