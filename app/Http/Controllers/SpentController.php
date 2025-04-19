<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spent;
use App\Models\Configuration;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\Helps;

class SpentController extends Controller
{
    /**
    * Displays the user's expense summary for a selected or default period.
    *
    * This method retrieves the user's spending data, either for a selected month (if provided in the request)
    * or for the default configured period. It calculates the total expenses, available money, 
    * remaining balance, and percentage of money spent. The method prepares all the necessary data 
    * to render the main dashboard view with financial indicators and formatted values.
    *
    * @param Request $request HTTP request containing optional filters like 'period', 'start_date', and 'end_date'.
    * 
    * @return \Illuminate\View\View Rendered view with the user's expense data, available periods, 
    * percentage spent, and additional footer information.
    */
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
            }
        } else {
            // usar los valores por defecte si no se usa el filtro
            $startDate = $request->input('start_date') ?? $this->getStartDateFromDatabase($user);
            $endDate = $request->input('end_date') ?? $this->getEndDateFromDatabase($user);
        }

        $data = $this->filterByPeriod($user->id, $startDate, $endDate);
        
        $countSpents = $this->getTotalSpentsByPeriod($user->id, $startDate, $endDate);

        $restMoney = $data['availableMoney'] - $data['totalPrice'];

        // Formatear los valores para la salida
        $formattedAvailableMoney = Helps::formatValue($data['availableMoney']);
        $formattedRestMoney = Helps::formatValue($restMoney);
        $formattedTotalPrice = Helps::formatValue($data['totalPrice']);
        $lastConfiguration = $this->getConfigurationForMonth($user->id);

        if(!empty($formattedAvailableMoney)){
            $percentageUsed = $this->checkSpending($data['totalPrice'], $data['availableMoney'], $lastConfiguration->expense_percentage_limit);
            if($percentageUsed['percentageUser'] >= $lastConfiguration->expense_percentage_limit){
                $message = true;
            }
        }else{
            $percentageUsed = [
                'percentageUser' => 0,
                'color' => 'green'
            ];
        }

        $currentDate = $this->getCurrentDate();
        $footerInformation = [
            'year' => $currentYear,
            'textInformation' => 'Expense Control'
        ];

        $monthlyBalance = [
            'available_money' => $formattedAvailableMoney,
            'total_price' => $formattedTotalPrice,
            'rest_money' => $formattedRestMoney,
            'count_spent' => $countSpents,
        ];
        
        // dd(Helps::getGitBranchName());

        return view('dashboard', [
            'spents' => $data['spents'],
            'user' => $user,
            'allPeriods' => $getAllPeriods,
            'monthly_balance' => $monthlyBalance,
            'lastConfiguration' => $lastConfiguration,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentDate' => $currentDate,
            'percentageUsed' => $percentageUsed,
            'message' => $message,
            'branchName' => Helps::getGitBranchName(),
            'footerInformation' => $footerInformation
        ]);
    }

    /**
    * Displays the view to create a new expense record.
    *
    * This method returns the form view used to register a new expense (Spent) for the authenticated user.
    * It passes the user instance to the view for any additional user-specific logic or data binding.
    *
    * @return \Illuminate\View\View View to create a new expense.
    */
    public function create(){
        $user = Auth::user();
        return view('spents.create-spent', [
            'user' => $user
        ]);
    }

    /**
    * Store a newly created expense record in the database.
    *
    * Validates the incoming request data to ensure the required 
    * fields are present. Creates a new Spent record associated 
    * with the authenticated user.
    * 
    * If the request method is POST, stores the data and redirects 
    * to the dashboard with a success message.
    * Otherwise, returns the create expense form view.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
    */
    public function store(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'expense_date' => 'required',
                'spentName' => 'required',
                'price' => 'required',
            ]);

            $spentName = trim($request->input('spentName'));
            $price = trim($request->input('price'));

            Spent::create([
                'expense_date' => $request->input('expense_date'),
                'name' => $spentName,
                'price' => $price,
                'user_id' => Auth::user()->id
            ]);

            return redirect()->route('dashboard')->with('success', 'Gasto agregado exitosamente.');
        }
        return view('spents.create-spent');
    }

    /**
    * Display the view to edit an existing expense record.
    *
    * Returns the form view for editing a specific expense (Spent) 
    * belonging to the authenticated user. 
    * Passes the expense instance and the user instance to the view 
    * for form population and additional user-specific logic.
    *
    * @param \App\Models\Spent $spent
    * @return \Illuminate\View\View
    */
    public function edit(Spent $spent){
        $user = Auth::user();

        $spent->name = trim($spent->name);
        $spent->price = trim($spent->price);

        return view('spents.edit-spent', [
            'spent' => $spent,
            'user' => $user
        ]);
    }

    /**
    * Update an existing expense record in the database.
    *
    * Updates the specified expense (Spent) with the provided data 
    * from the request. After a successful update, redirects the user 
    * back to the dashboard view.
    *
    * @param \Illuminate\Http\Request $request
    * @param \App\Models\Spent $spent
    * @return \Illuminate\Http\RedirectResponse
    */
    public function update(Request $request, Spent $spent)
    {
        $input = $request->all();
        $spent -> update($input);
        return redirect('dashboard');
    }

    /**
    * Delete an existing expense record from the database.
    *
    * Deletes the specified expense (Spent) and redirects the user 
    * back to the dashboard view while preserving the current 
    * year and month filters.
    *
    * @param \Illuminate\Http\Request $request
    * @param \App\Models\Spent $spent
    * @return \Illuminate\Http\RedirectResponse
    */
    public function destroy(Request $request, Spent $spent){
        $spent->delete();
        $year_filter = $request->input('year');
        $month_filter = $request->input('month');

        return redirect()->route('dashboard', ['year' => $year_filter, 'month' => $month_filter]);
    }

    /**
    * Retrieve all available periods for the authenticated user.
    *
    * This method fetches all the periods (start and end dates) 
    * along with the available money for each period configured 
    * by the user.
    *
    * @param int $userId The ID of the authenticated user.
    * @return \Illuminate\Support\Collection List of periods with start date, end date, and available money.
    */
    private function getAllPeriods($userId){
        $periods = Configuration::select('start_counting', 'end_counting', 'month_available_money')
                                ->where('user_id', $userId)
                                ->get();
        return $periods;
    }

    /**
    * Filter expenses and retrieve related data for a specific period.
    *
    * This method retrieves the expenses, available money, and total 
    * amount spent by the authenticated user within the specified 
    * start and end dates.
    *
    * @param int $userId The ID of the authenticated user.
    * @param string $startDate The start date of the period (Y-m-d).
    * @param string $endDate The end date of the period (Y-m-d).
    * @return array Contains:
    *  - spents: List of expenses within the period.
    *  - availableMoney: Money available in the selected period.
    *  - totalPrice: Total amount of expenses in the selected period.
    */
    private function filterByPeriod($userId, $startDate, $endDate){
        $spents = $this->getFilteredSpentsByPeriod($userId, $startDate, $endDate);
        $availableMoney = $this->getAvailableMoneyByPeriod($userId, $startDate, $endDate);
        $totalPrice = $this->getTotalPriceByPeriod($userId, $startDate, $endDate);
        
        return [
            'spents' => $spents,
            'availableMoney' => $availableMoney,
            'totalPrice' => $totalPrice
        ];
    }

    /**
    * Get the start date of the latest configured period for the user.
    *
    * Retrieves the start_counting date from the most recent 
    * Configuration record of the authenticated user. 
    * If no configuration exists, returns the current date.
    *
    * @param \App\Models\User $user The authenticated user instance.
    * @return \Illuminate\Support\Carbon|string Start date from the latest configuration or current date.
    */
    private function getStartDateFromDatabase($user) {
        $configuration = Configuration::where('user_id', $user->id)
                                      ->latest()
                                      ->first();

        return $configuration ? $configuration->start_counting : Carbon::now();
    }
    
    /**
    * Get the end date of the latest configured period for the user.
    *
    * Retrieves the end_counting date from the most recent 
    * Configuration record of the authenticated user. 
    * If no configuration exists, returns the current date.
    *
    * @param \App\Models\User $user The authenticated user instance.
    * @return \Illuminate\Support\Carbon|string End date from the latest configuration or current date.
    */
    private function getEndDateFromDatabase($user) {
        $configuration = Configuration::where('user_id', $user->id)
                                      ->latest()
                                      ->first();
    
        return $configuration ? $configuration->end_counting : Carbon::now();
    }

    /**
    * Format the given month to a two-digit string.
    *
    * Ensures that the month input is always a two-character 
    * string by padding with a leading zero if necessary.
    * Example: 3 becomes '03'.
    *
    * @param int|string $monthInput The month value to format.
    * @return string The formatted month as a two-digit string.
    */
    private function formatMonth($monthInput) {
        return intval($monthInput) < 10 
            ? str_pad($monthInput, 2, '0', STR_PAD_LEFT) 
            : $monthInput;
    }

    /**
    * Retrieve filtered expenses by year and month.
    *
    * Fetches the Spent records for the given user, 
    * applying optional filters for month and year. 
    * Results are ordered by the expense date.
    *
    * @param int $userId The ID of the authenticated user.
    * @param string|null $monthFilter Optional month filter (two-digit string).
    * @param string|null $yearFilter Optional year filter (four-digit string).
    * 
    * @return \Illuminate\Database\Eloquent\Collection List of filtered Spent records.
    */
    private function getFilteredSpentsByYearMonth($userId, $monthFilter, $yearFilter) {
        $query = Spent::where('user_id', $userId);

        if ($monthFilter) {
            $query->whereMonth('expense_date', $monthFilter);
        }

        if ($yearFilter) {
            $query->whereYear('expense_date', $yearFilter);
        }
    
        return $query->orderBy('expense_date')->get();
    }
    
    /**
    * Retrieve filtered expenses within a specific date range.
    *
    * Fetches Spent records for the given user where the 
    * expense date falls between the provided start and end dates.
    * 
    * Additionally, formats the price with thousand separators 
    * and formats the date as 'dd/mm/YYYY' for presentation purposes.
    *
    * @param int $userId The ID of the authenticated user.
    * @param string $startDate Start date of the filter period (YYYY-MM-DD).
    * @param string $endDate End date of the filter period (YYYY-MM-DD).
    * 
    * @return \Illuminate\Database\Eloquent\Collection List of formatted Spent records.
    */
    private function getFilteredSpentsByPeriod($userId, $startDate, $endDate){
        $spents = Spent::where('user_id', $userId)
                            ->whereBetween('expense_date', [sprintf("'%s'",$startDate), sprintf("'%s'", $endDate)])
                            ->orderBy('expense_date', 'desc')
                            ->get();
        
        foreach($spents as $spent){
            $spent->name = trim($spent->name);
            $spent->price = number_format($spent->price, 0, '', '.');
            $spent->expense_date = Carbon::parse($spent->expense_date)->format('d/m/Y');
        }

        return $spents;
    }

    /**
    * Retrieve all expense records for the given user.
    * 
    * Fetches all Spent records associated with the user ID provided,
    * regardless of any date filter. 
    * 
    * Additionally, formats the expense_date attribute to 'dd/mm/YYYY'
    * for presentation purposes.
    *
    * @param int $userId The ID of the authenticated user.
    * 
    * @return \Illuminate\Database\Eloquent\Collection List of formatted Spent records.
    */
    private function getAllSpentForMonth($userId){
        $spents = Spent::where('user_id', $userId)
                            ->get();
        
        foreach ($spents as $spent) {
            $spent->expense_date = Carbon::parse($spent->expense_date)->format('d/m/Y');
        }
        
        return $spents;
    }

    /**
    * Retrieve the available money for a user within a specific period.
    * 
    * If a start and end date are provided, it looks for a Configuration
    * whose date range intersects with the given period.
    * 
    * If no dates are provided, it fetches the available money from the 
    * current active Configuration based on today's date.
    * 
    * @param int $userId The ID of the authenticated user.
    * @param string|null $startDate Optional start date to filter.
    * @param string|null $endDate Optional end date to filter.
    * 
    * @return int Available money configured for the user within the period.
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
    * Get the total number of Spents for a user within a specific period.
    * 
    * It joins the Spents with the Configurations table to ensure that
    * the Spents belong to the correct configuration period.
    * 
    * @param int $userId The ID of the authenticated user.
    * @param string|null $startDate Optional start date to filter.
    * @param string|null $endDate Optional end date to filter.
    * 
    * @return int Total number of spents within the period.
    */
    private function getTotalSpentsByPeriod($userId, $startDate = null, $endDate = null){
        if($startDate && $endDate){
            $count = Spent::join('configurations as c', function ($join){
                $join->on('spents.expense_date', '>=', 'c.start_counting')
                    ->on('spents.expense_date', '<=', 'c.end_counting'); 
            })
            ->where('c.start_counting', $startDate)
            ->where('c.end_counting', $endDate)
            ->where('spents.user_id', $userId)
            ->count();
        }

        return $count;
    }
    
    /**
    * Get the total price of Spents filtered by year and month.
    *
    * This method sums the 'price' of all Spents that belong 
    * to the specified year and month.
    *
    * @param int|null $yearFilter  Year to filter the Spents.
    * @param int|null $monthFilter Month to filter the Spents.
    *
    * @return float Total price of the filtered Spents.
    */
    private function getTotalPriceByYearMonth($yearFilter, $monthFilter) {
        $query = Spent::query();
    
        if ($yearFilter) {
            $query->whereRaw('EXTRACT(YEAR FROM "expense_date") = ?', [$yearFilter]);
        }
    
        if ($monthFilter) {
            $query->whereRaw('EXTRACT(MONTH FROM "expense_date") = ?', [$monthFilter]);
        }
    
        return $query->sum('price');
    }

    /**
    * Get the total price of Spents within a specific period.
    *
    * Sums the 'price' of all Spents that belong to the authenticated user 
    * and fall within the provided date range.
    *
    * @param int $userId         ID of the authenticated user.
    * @param string $startDate   Start date of the period (Y-m-d format).
    * @param string $endDate     End date of the period (Y-m-d format).
    *
    * @return float Total price of the filtered Spents.
    */
    private function getTotalPriceByPeriod($userId, $startDate, $endDate) {
        return Spent::where('user_id', $userId)
                        ->whereBetween('expense_date', [$startDate, $endDate])
                        ->sum('price');
    }
    
    /**
    * Get the latest configuration for a specific user.
    *
    * Retrieves the most recent Configuration record based on the 'end_counting' field.
    * Formats the date fields of the Configuration and returns it.
    *
    * @param int $userId   ID of the authenticated user.
    * 
    * @return Configuration|null  Latest Configuration found or null if not exists.
    */
    private function getConfigurationForMonth($userId) {
         // Obtener la configuración para el mes y año específicos
        $configuration = Configuration::where('user_id', $userId)
                                        ->orderBy('end_counting', 'desc')
                                        ->first();
    
        if ($configuration) {
            $this->formatConfigurationDates($configuration);
        }
        return $configuration;
    }
    
    /**
    * Format the start_counting and end_counting dates of a Configuration.
    *
    * This method updates the Configuration object by formatting the start_counting
    * and end_counting dates to a consistent format (d/m/Y).
    *
    * @param Configuration $configuration  Configuration model instance (passed by reference).
    * 
    * @return void
    */
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
    
    /**
    * Format a date from Y-m-d or Y/m/d to d/m/y.
    *
    * @param string $date  The date string to format.
    * @return string       The formatted date.
    */
    private function formatDate($date) {
        return Carbon::createFromFormat('Y-m-d', str_replace('/', '-', $date))->format('d/m/y');
    }

    /**
    * Check user spending percentage and assign color based on limit.
    *
    * @param float $totalPrice
    * @param float $availableMoney
    * @param int $limit
    * @return array
    */
    private function checkSpending($totalPrice, $availableMoney, $limit){
        if ($totalPrice && $availableMoney) {
            $percentage = round(($totalPrice / $availableMoney) * 100, 1, PHP_ROUND_HALF_UP);

            return [
                'percentageUser' => $percentage,
                'color' => $percentage >= $limit ? 'red' : 'green',
            ];
        }

        return [
            'percentageUser' => 0,
            'color' => 'green',
        ];
    }
    
    private function getMonths() {
        return Helps::getNameMonths();
    }

    private function getCurrentDate(){
        return Helps::getDate();
    }

    private function amountOfExpenses(){
        
    }
    
}
