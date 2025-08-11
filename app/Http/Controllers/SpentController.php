<?php
declare(strict_types=1);

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
        $hasConfiguration = true;
        $hasBothConfig = false;
        $onlyFilter = false;
        $user = Auth::user();
        $config = Helps::getAllConfiguration($user->id);

        $type = $request->query('type', 'personal'); // default: personal
        $selectedMonth = $request->input('period');
        $filterText = $request->input('search');

        $getAllPeriods = Helps::getAllPeriods($user->id, 'personal');

        if($selectedMonth){
            $selectedPeriod = $getAllPeriods->firstWhere('month_available_money', $selectedMonth);
            if($selectedPeriod){
                $startDate = $selectedPeriod->start_counting;
                $endDate = $selectedPeriod->end_counting;
            }
        } else {
            // usar los valores por defecte si no se usa el filtro
            $startDate = $request->input('start_date') ?? Helps::getStartDateFromDatabase($user->id, 'personal');
            $endDate = $request->input('end_date') ?? Helps::getEndDateFromDatabase($user->id, 'personal');
        }

        $getPersonalData = PersonalConfigurationController::getPersonalData();
        $getBusinessData = BusinessConfigurationController::getBusinessData();
        $lastConfiguration = $this->getConfigurationForMonth($user->id);

        if($getPersonalData->isNotEmpty() && $getBusinessData->isNotEmpty()){
            $hasBothConfig = true;
        }

        if(!$filterText){
            $data = Helps::filterByPeriod($user->id, $startDate, $endDate, $type);
        }else{
            $onlyFilter = true;
            $data = Helps::filterByText($user->id, $filterText, $type);
            return view('dashboard',[
                'spents' => $data,
                'user' => $user,
                'allPeriods' => $getAllPeriods,
                'message' => $message,
                'currentDate' => Helps::getDate(),
                'lastConfiguration' => $lastConfiguration,
                'hasConfiguration' => $hasConfiguration,
                'type' => $type,
                'hasBothConfig' => $hasBothConfig,
                'onlyFilter' => $onlyFilter,
                'branchName' => Helps::getGitBranchName(),
            ]);
        }

        $countSpents = $this->getTotalSpentsByPeriod($user->id, $startDate, $endDate);
        
        if($data['availableMoney'] != 0){
            $restMoney = $data['availableMoney'] - $data['totalPrice'];
        }else {
            $restMoney = 0;
        }
            
        // Formatear los valores para la salida
        $formattedAvailableMoney = Helps::formatValue($data['availableMoney']);
        $formattedRestMoney = Helps::formatValue($restMoney);
        $formattedTotalPrice = Helps::formatValue($data['totalPrice']);
            
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
                
        $monthlyBalance = [
            'available_money' => $formattedAvailableMoney,
            'total_price' => $formattedTotalPrice,
            'rest_money' => $formattedRestMoney,
            'count_spent' => $countSpents,
        ];
    
        if($config->isEmpty()){
            $hasConfiguration = false;
        }

        return view('dashboard', [
            'spents' => $data['spents'],
            'user' => $user,
            'allPeriods' => $getAllPeriods,
            'monthly_balance' => $monthlyBalance,
            'lastConfiguration' => $lastConfiguration,
            'currentDate' => Helps::getDate(),
            'percentageUsed' => $percentageUsed,
            'message' => $message,
            'branchName' => Helps::getGitBranchName(),
            'hasConfiguration' => $hasConfiguration,
            'type' => $type,
            'hasBothConfig' => $hasBothConfig,
            'onlyFilter' => $onlyFilter
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
    public function create(Request $request){
        
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
        return view('abm.create');
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

        return view('abm.edit', [
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
        $spent->update($input);
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
    private function getTotalSpentsByPeriod(int $userId, string $startDate = null, string $endDate = null): int {
        if($startDate && $endDate){
            $count = Spent::join('personal_configurations as c', function ($join){
                $join->on('spents.expense_date', '>=', 'c.start_counting')
                    ->on('spents.expense_date', '<=', 'c.end_counting')
                    ->on('spents.user_id', '=', 'c.user_id');
            })
            ->where('c.start_counting', $startDate)
            ->where('c.end_counting', $endDate)
            ->where('spents.user_id', $userId)
            ->count();
        }

        return $count;
    }
    
    /**
    * Get the latest configuration for a specific user.
    *
    * Retrieves the most recent Configuration record based on the 'end_counting' field.
    * Formats the date fields of the Configuration and returns it.
    *
    * @param int $userId   ID of the authenticated user.
    * 
    * @return PersonalConfiguration|null  Latest Configuration found or null if not exists.
    */
    private function getConfigurationForMonth(int $userId): ?PersonalConfiguration {
        $configuration = PersonalConfiguration::where('user_id', $userId)
                                        ->orderBy('end_counting', 'desc')
                                        ->first();
    
        if ($configuration instanceof PersonalConfiguration) {
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
    * @param PersonalConfiguration $configuration  Configuration model instance (passed by reference).
    * 
    * @return void
    */
    private function formatConfigurationDates(PersonalConfiguration $configuration): void {
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
    private function formatDate(string $date): string {
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
    private function checkSpending(int $totalPrice,int $availableMoney,int $limit): array{
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
    
}
