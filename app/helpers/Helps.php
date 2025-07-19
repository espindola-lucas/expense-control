<?php
namespace App\Helpers;
use Carbon\Carbon;
use App\Enums\MonthEnum;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use App\Models\PersonalConfiguration;
use App\Models\BusinessConfiguration;
use App\Models\Spent;
use App\Models\Sell;

class Helps{
    public static function getNameMonths(){
        return MonthEnum::getMonths();
    }

    public static function getDate(){
        $currentDate = Carbon::now()->format('d/m/Y');
        return $currentDate;
    }

    public static function getMonthNameByKey($key){
        return MonthEnum::getMonthNameByKey($key);
    }

    public static function formatValue($param) {
        return number_format($param, 0, '', '.');
    }
    
    public static function getGitBranchName(){
        $gitBranch = base_path('.git/HEAD');
        $headContent = file_get_contents($gitBranch);
        if(strpos($headContent, 'ref:') === 0){
            $branch = trim(str_replace('ref: refs/heads/', '', $headContent));
        }
        return $branch;
    }

    public static function saveQuery($query, string $filename = 'consulta-sql')
    {

        // get SQL and bindings according to the query type
        if ($query instanceof EloquentBuilder || $query instanceof QueryBuilder) {
            $sql = $query->toSql();
            $bindings =  $query->getBindings();
        } else {
            throw new \InvalidArgumentException('Expected instance of Query\Builder or Eloquent\Builder');
        }

        foreach ($bindings as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . addslashes($binding) . "'";
            $sql = preg_replace('/\?/', $value, $sql, 1);
        }

        $path = storage_path("logs/{$filename}.txt");

        File::append($path, $sql . PHP_EOL);
    }

    public static function executeLogQuery($query, string $filename = 'resultados-query'){
        if ($query instanceof EloquentBuilder || $query instanceof QueryBuilder){
            $results = $query->get();
        } else {
            throw new \InvalidArgumentException('Expected instance of Query\Builder or Eloquent\Builder');
        }

        // convert results to readable JSON
        $jsonData = $results->toJson(JSON_PRETTY_PRINT || JSON_UNESCAPED_UNICODE);

        $path = storage_path("logs/{$filename}.json");
        File::put($path, $jsonData);
    }

    public static function getAllConfiguration($userId, $type = 'personal') {
        // map type to model and type name
        $configMap = [
            'personal' => [
                'model' => PersonalConfiguration::class,
                'typeName' => 'Personal'
            ],
            'business' => [
                'model' => BusinessConfiguration::class,
                'typeName' => 'Comercio'
            ]
        ];

        if (!isset($configMap[$type])) {
            throw new \InvalidArgumentException('Tipoe de configuracion invalido: $type');
        }

        $modelClass = $configMap[$type]['model'];
        $typeName = $configMap[$type]['typeName'];

        // get configs
        $configs = $modelClass::where('user_id', $userId)
                            ->orderBy('id', 'desc')
                            ->get();

        foreach ($configs as $config) {
            $config->start_counting = Carbon::parse($config->start_counting)->format('d/m/Y');
            $config->end_counting = Carbon::parse($config->end_counting)->format('d/m/Y');
            $config->configuration_type = $typeName;
            $config->real_id = $config->id;
            $config->real_model = $modelClass;
        }

        return $configs;
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
    public static function getAllPeriods($user, $tabla){
        if($tabla === 'personal'){
            $periods = PersonalConfiguration::select('start_counting', 'end_counting', 'month_available_money')
                                ->where('user_id', $user)
                                ->get();
        
            return $periods;
        }elseif($tabla === 'business'){
            $periods = BusinessConfiguration::select('start_counting', 'end_counting')
                                ->where('user_id', $user)
                                ->get();
        
            return $periods;
        }
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
    public static function getStartDateFromDatabase($user, $tabla){
        if($tabla === 'personal'){
            $configuration = PersonalConfiguration::where('user_id', $user)
                                ->latest()
                                ->first();
        
            return $configuration ? $configuration->start_counting : Carbon::now();
        }elseif($tabla === 'business'){
            $configuration = BusinessConfiguration::where('user_id', $user)
                                ->latest()
                                ->first();
        
            return $configuration ? $configuration->start_counting : Carbon::now();
        }
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
    public static function getEndDateFromDatabase($user, $tabla){
        if($tabla === 'personal'){
            $configuration = PersonalConfiguration::where('user_id', $user)
                                ->latest()
                                ->first();
        
            return $configuration ? $configuration->end_counting : Carbon::now();
        }elseif($tabla === 'business'){
            $configuration = BusinessConfiguration::where('user_id', $user)
                                ->latest()
                                ->first();
        
            return $configuration ? $configuration->end_counting : Carbon::now();
        }
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
    public static function filterByPeriod($userId, $startDate, $endDate, $type){
        if($type === 'personal'){
            $spents = self::getFilteredSpentsByPeriod($userId, $startDate, $endDate);
            $availableMoney = self::getAvailableMoneyByPeriod($userId, $startDate, $endDate);
            $totalPrice = self::getTotalPriceByPeriod($userId, $startDate, $endDate);
            
            return [
                'spents' => $spents,
                'availableMoney' => $availableMoney,
                'totalPrice' => $totalPrice
            ];
        }elseif($type === 'business'){
            $sells = self::getFilteredSellsByPeriod($userId, $startDate, $endDate);

            return [
                'sells' => $sells,
            ];
        }
    }

    private static function getFilteredSpentsByPeriod($userId, $startDate, $endDate){
        $informations = Spent::where('user_id', $userId)
                        ->whereBetween('expense_date', [sprintf("'%s'",$startDate), sprintf("'%s'", $endDate)])
                        ->orderBy('expense_date', 'desc')
                        ->get();

        foreach($informations as $info){
            $info->name = trim($info->name);
            $info->price = number_format($info->price, 0, '', '.');
            $info->expense_date = Carbon::parse($info->expense_date)->format('d/m/Y');
        }

        return $informations;
    }

    private static function getAvailableMoneyByPeriod($userId, $startDate, $endDate){
        $query = PersonalConfiguration::where('user_id', $userId);

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

    private static function getTotalPriceByPeriod($userId, $startDate, $endDate){
        return Spent::where('user_id', $userId)
                        ->whereBetween('expense_date', [$startDate, $endDate])
                        ->sum('price');
    }

    private static function getFilteredSellsByPeriod($userId, $startDate, $endDate){
        $informations = Sell::where('user_id', $userId)
                        ->whereBetween('sell_date', [sprintf("'%s'",$startDate), sprintf("'%s'", $endDate)])
                        ->orderBy('sell_date', 'desc')
                        ->get();

        foreach($informations as $info){
            $info->name = trim($info->name);
            $info->price = number_format($info->price, 0, '', '.');
            $info->sell_date = Carbon::parse($info->sell_date)->format('d/m/Y');
        }

        return $informations;
    }
}

?>
