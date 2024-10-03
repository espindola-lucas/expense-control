<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Configuration;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function index(Request $request) {
        $percentageUsed = null;
        $message = false;

        $currentYear = now()->year;
        $user = Auth::user();
    
        $selectedMonth = $request->input('period');

        $getAllPeriods = $this->getAllPeriods($user->id);

        if($selectedMonth){
            $selectedPeriod = $getAllPeriods->firstWhere('month_available_money', $selectedMonth);
            if($selectedPeriod){
                $startDate = $selectedPeriod->start_counting;
                $endDate = $selectedPeriod->end_counting;        
            } else {
                $startDate = $this->getStartDateFromDatabase($user);
                $endDate = $this->getEndDateFromDatabase($user);
            }
        } else {
            // usar los valores por defecte si no se usa el filtro
            $startDate = $request->input('start_date') ?? $this->getStartDateFromDatabase($user);
            $endDate = $request->input('end_date') ?? $this->getEndDateFromDatabase($user);
        }

        $data = $this->filterByPeriod($user->id, $startDate, $endDate);

        $restMoney = $data['availableMoney'] - $data['totalPrice'];

        // Formatear los valores para la salida
        $formattedAvailableMoney = $this->formatValue($data['availableMoney']);
        $formattedRestMoney = $this->formatValue($restMoney);
        $formattedTotalPrice = $this->formatValue($data['totalPrice']);
        $lastConfiguration = $this->getConfigurationForMonth($user->id);
        // dd($lastConfiguration->expense_percentage_limit);
        if(!empty($formattedAvailableMoney)){
            $percentageUsed = $this->checkSpending($data['totalPrice'], $data['availableMoney']);
            if($percentageUsed >= $lastConfiguration->expense_percentage_limit){
                $message = true;
            }
        }

        $currentDate = $this->getCurrentDate();
        $footerInformation = [
            'year' => $currentYear,
            'textInformation' => 'Expense Control'
        ];
        
        return view('dashboard', [
            'products' => $data['products'],
            'user' => $user,
            'allPeriods' => $getAllPeriods,
            'totalPrice' => $formattedTotalPrice,
            'available_money' => $formattedAvailableMoney,
            'rest_money' => $formattedRestMoney,
            'lastConfiguration' => $lastConfiguration,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentDate' => $currentDate,
            'percentageUsed' => round($percentageUsed, 1),
            'message' => $message,
            'footerInformation' => $footerInformation
        ]);
    }

    public function create(){
        $user = Auth::user();
        return view('products.create-product', [
            'user' => $user
        ]);
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

    private function getAllPeriods($userId){
        $periods = Configuration::select('start_counting', 'end_counting', 'month_available_money')
                                ->where('user_id', $userId)
                                ->get();
        return $periods;
    }

    private function filterByPeriod($userId, $startDate, $endDate){
        $products = $this->getFilteredProductsByPeriod($userId, $startDate, $endDate);
        $availableMoney = $this->getAvailableMoneyByPeriod($userId, $startDate, $endDate);
        $totalPrice = $this->getTotalPriceByPeriod($userId, $startDate, $endDate);
        
        return [
            'products' => $products,
            'availableMoney' => $availableMoney,
            'totalPrice' => $totalPrice
        ];
    }

    /**
	* Get start and end dates from the database
	* @param int $user receives the user ID
	* @return string returns the start date of the period
	*/
    private function getStartDateFromDatabase($user) {
        $configuration = Configuration::where('user_id', $user->id)
                                      ->latest()
                                      ->first();

        return $configuration ? $configuration->start_counting : Carbon::now();
    }
    
    /**
	* Get start and end dates from the database
	* @param int $user receives the user ID
	* @return string returns the end date of the period
	*/
    private function getEndDateFromDatabase($user) {
        $configuration = Configuration::where('user_id', $user->id)
                                      ->latest()
                                      ->first();
    
        return $configuration ? $configuration->end_counting : Carbon::now();
    }

    /**
	* Formats the month number, if it is less than 10, it adds a 0 in front (example. 8 -> 08)
	* @param int $monthInput month number
	* @return int formatted month number
	*/
    private function formatMonth($monthInput) {
        return intval($monthInput) < 10 ? str_pad($monthInput, 2, '0', STR_PAD_LEFT) : $monthInput;
    }
    
    /**
	* Gets the products of each user, depending on the filter used
	* @param int $userId user ID
    * @param int $yearFilter filtered year number
    * @param int $monthFilter filtered month number
	* @return array arrangement of recovered data
	*/
    private function getFilteredProductsByYearMonth($userId, $monthFilter, $yearFilter) {
        $query = Product::where('user_id', $userId);

        if ($monthFilter) {
            $query->whereMonth('expense_date', $monthFilter);
        }

        if ($yearFilter) {
            $query->whereYear('expense_date', $yearFilter);
        }
    
        return $query->orderBy('expense_date')->get();
    }
    
    /**
	* Products are filtered by the configured start and end dates
	* @param int $userId user ID
    * @param int $startDate start day
    * @param int $endDate end day
	* @return array arrangement of recovered data
	*/
    private function getFilteredProductsByPeriod($userId, $startDate, $endDate){
        $products = Product::where('user_id', $userId)
                            ->whereBetween('expense_date', [sprintf("'%s'",$startDate), sprintf("'%s'", $endDate)])
                            ->orderBy('expense_date', 'desc')
                            ->get();
        
        foreach($products as $product){
            $product->price = number_format($product->price, 0, '', '.');
        }

        return $products;
    }

    /**
	* Returns all products of the logged in user
	* @param int $userId user ID
	* @return array arrangement of recovered data
	*/
    private function getAllProductsForMonth($userId){
        $products = Product::where('user_id', $userId)
                            ->get();
        
        foreach ($products as $product) {
            $product->expense_date = Carbon::parse($product->expense_date)->format('d/m/Y');
        }
        
        return $products;
    }

    /**
	* Returns the available money for the configured month
	* @param int $userId user ID
    * @param int $startDate start day
    * @param int $endDate end day
	* @return int money available
	*/
    private function getAvailableMoneyByPeriod($userId, $startDate = null, $endDate = null) {
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
    
    /**
	* Returns the money spent for an entire month
    * @param int $yearFilter year
    * @param int $monthFilter month
	* @return int sum spent
	*/
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

    /**
	* Returns the money spent for the configured date range
    * @param int $startDate start day
    * @param int $endDate end day
	* @return int sum spent
	*/
    private function getTotalPriceByPeriod($userId, $startDate, $endDate) {
        return Product::where('user_id', $userId)
                        ->whereBetween('expense_date', [$startDate, $endDate])
                        ->sum('price');
    }
    
    /**
	* Formats the value of a number
    * @param int $param value
	* @return int value formatted
	*/
    private function formatValue($param) {
        return number_format($param, 0, '', '.');
    }
    
    private function getConfigurationForMonth($userId) {
         // Obtener la configuración para el mes y año específicos
        $configuration = Configuration::where('user_id', $userId)
                                        ->orderBy('end_counting', 'desc')
                                        ->first();
    
        if ($configuration) {
            $this->formatConfigurationDates($configuration);
    
            $endCounting = Carbon::createFromFormat('d/m/y', $configuration->end_counting);
        }
        return $configuration;
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

    private function checkSpending($totalPrice, $availableMoney){
        $percentageUser = ($totalPrice / $availableMoney) * 100;
        return $percentageUser;
    }
    
    private function getMonths() {
        return \Helps::getNameMonths();
    }

    private function getCurrentDate(){
        return \Helps::getDate();
    }
    
}
