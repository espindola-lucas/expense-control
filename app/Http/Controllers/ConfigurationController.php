<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Configuration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use App\Models\Spent;
use App\Helpers\Helps;

class ConfigurationController extends Controller
{
    public function index(Request $request){
        $months = Helps::getNameMonths();
        $user = Auth::user();
        $allConfigurations = $this->getAllConfiguration($user->id);
        
        foreach ($allConfigurations as $configuration) {
            $configuration->available_money = $this->formatMoney($configuration->available_money);
            $month_name = Helps::getMonthNameByKey($configuration->month_available_money);
            $configuration->month_available_money = "{$configuration->month_available_money} - {$month_name}";

            $currentDate = Carbon::now();
            $endDate = Carbon::createFromFormat('d/m/Y', $configuration->end_counting);
            $configuration->show_edit_button = $currentDate->lte($endDate);
        }

        $currentYear = now()->year;
        $footerInformation = [
            'year' => $currentYear,
            'textInformation' => 'Expense Control'
        ];
        return view('configuration', [
            'months' => $months,
            'configurations' => $allConfigurations,
            'user' => $user,
            'footerInformation' => $footerInformation
        ]);
    }

    public function create(){
        $months = Helps::getNameMonths();
        $user = Auth::user();

        return view('configuration.create-configuration', [
            'months' => $months,
            'user' => $user
        ]);
    }

    public function store(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'start_counting' => 'required',
                'end_counting'  => 'required',
                'available_money' => 'required',
                'month_available_money' => 'required',
                'expense_percentage_limit' => 'required'
            ]);

            $start_counting = $request->input('start_counting') ?: null;
            $end_counting = $request->input('end_counting') ?: null;

            Configuration::create([
                'start_counting' => $start_counting,
                'end_counting' => $end_counting,
                'available_money' => $request->input('available_money'),
                'month_available_money' => $request->input('month_available_money'),
                'expense_percentage_limit' => $request->input('expense_percentage_limit'),
                'user_id' => Auth::user()->id
            ]);

            return Redirect::back()->with('success', 'Configuración guardada exitosamente.');
        }
    }

    public function show($id){
        $configId = Configuration::find($id);
        $isDefaultMonth = false;
        $user = Auth::user();
        $configuration = $this->getAllConfiguration($user->id);
        $selectedMonth = $configuration->first()->month_available_money ?? null;
        $months = Helps::getNameMonths();
        return view('configuration.show-configuration', [
            'user' => $user,
            'configuration' => $configId,
            'isDefaultMonth' => $isDefaultMonth,
            'selectedMonth' => $selectedMonth,
            'months' => $months
        ]);
    }

    public function edit(Configuration $configuration){
        $months = Helps::getNameMonths();
        $user = Auth::user();
        $selectedMonth = $configuration['month_available_money'];
        $currentYear = now()->year;
        $footerInformation = [
            'year' => $currentYear,
            'textInformation' => 'Expense Control'
        ];
        
        $isDefaultMonth = true;
        
        return view('configuration.edit-configuration', [
            'user' => $user,
            'configuration' => $configuration,
            'months' => $months,
            'isDefaultMonth' => $isDefaultMonth,
            'selectedMonth' => $selectedMonth,
            'footerInformation' => $footerInformation
        ]);
    }

    public function update(Request $request, Configuration $configuration){
        $input = $request->all();
        $configuration->update($input);
        return redirect('configuration');
    }

    public function destroy($id){
        $config = Configuration::findOrFail($id);
        $config->delete();
        
        return redirect('configuration');
    }

    private function getAllConfiguration($userId){
        $configurations = Configuration::where('user_id', $userId)
                                        ->orderBy('id', 'desc')
                                        ->get();
        
        foreach($configurations as $configuration){
            $configuration->start_counting = Carbon::parse($configuration->start_counting)->format('d/m/Y');
            $configuration->end_counting = Carbon::parse($configuration->end_counting)->format('d/m/Y');
        }
        
        return $configurations;
    }

    private function formatMoney($amount) {
        if (is_numeric($amount)) {
            return number_format($amount, 0, ',', '.');
        }
        return $amount;
    }

    public function getInfo() {
        $user_id = auth()->id();

        $latestConfiguration = Configuration::orderBy('id', 'desc')->first();

        if($latestConfiguration){
            $startDate = $latestConfiguration->start_counting;
            $endDate = $latestConfiguration->end_counting;

            $available_money = $this->getAvailableMoneyByPeriod($user_id, $startDate, $endDate);

            $totalSpent = $this->getTotalPriceByPeriod($user_id, $startDate, $endDate);

            $remainingMoney = $available_money - $totalSpent;

            return response()->json([
                'info' => Helps::formatValue($remainingMoney)
            ]);
        }

        return response()->json([
            'info' => 0
        ]);
    }

    /**
	* Returns the money spent for the configured date range
    * @param int $startDate start day
    * @param int $endDate end day
	* @return int sum spent
	*/
    private function getTotalPriceByPeriod($userId, $startDate, $endDate) {
        return Spent::where('user_id', $userId)
                        ->whereBetween('expense_date', [$startDate, $endDate])
                        ->sum('price');
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
}