<?php
declare(strict_types=1);

namespace App\Helpers;

use Carbon\Carbon;
use App\Enums\MonthEnum;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Collection;
use App\Models\PersonalConfiguration;
use App\Models\BusinessConfiguration;
use App\Models\Spent;
use App\Models\Sell;

class Helps{
    public static function getNameMonths(){
        return MonthEnum::getMonths();
    }

    public static function getDate(): string
    {
        return Carbon::now()->format('d/m/Y');
    }

    public static function getMonthNameByKey(string $key): string
    {
        return MonthEnum::getMonthNameByKey($key);
    }

    public static function formatValue(float|int|null $param): string
    {
        if ($param === null){
            return '0';
        }

        return number_format($param, 0, '', '.');
    }
    
    public static function getGitBranchName(): string
    {
        $gitHeadPath = base_path('.git/HEAD');
        
        if(!file_exists($gitHeadPath)) {
            return 'unknown';
        }
        $headContent = file_get_contents($gitHeadPath);
        
        if(strpos($headContent, 'ref:') === 0){
            return trim(str_replace('ref: refs/heads/', '', $headContent));
        }
        return 'unknown';
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

    public static function getAllConfiguration(int $userId, string $type = 'personal'): Collection
    {
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
            throw new \InvalidArgumentException('Tipo de configuracion invalido: {$type}');
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
            $config->real_model = class_basename($modelClass);
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
    public static function getAllPeriods(int $user, string $tabla): Collection
    {
        if($tabla === 'personal'){
            return PersonalConfiguration::select('start_counting', 'end_counting', 'month_available_money')
                                ->where('user_id', $user)
                                ->get();
        
        }
        
        if($tabla === 'business'){
            return BusinessConfiguration::select('start_counting', 'end_counting')
                                ->where('user_id', $user)
                                ->get();
        }

        return collect();
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
    public static function getStartDateFromDatabase(int $user, string $tabla): string
    {
        if($tabla === 'personal'){
            return PersonalConfiguration::where('user_id', $user)
                                ->latest()
                                ->value('start_counting') ?? Carbon::now()->toDateString();
        }elseif($tabla === 'business'){
            return BusinessConfiguration::where('user_id', $user)
                                ->latest()
                                ->value('start_counting') ?? Carbon::now()->toDateString();
        }

        return Carbon::now()->toDateString();
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
    public static function getEndDateFromDatabase(int $user, string $tabla): string
    {
        if($tabla === 'personal'){
            return PersonalConfiguration::where('user_id', $user)
                                ->latest()
                                ->value('end_counting') ?? Carbon::now()->toDateString();
        }elseif($tabla === 'business'){
            return BusinessConfiguration::where('user_id', $user)
                                ->latest()
                                ->value('end_counting') ?? Carbon::now()->toDateString();
        }
        return Carbon::now()->toDateString();
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
    public static function filterByPeriod(int $userId, string $startDate, string $endDate, string $type): array
    {
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

    public static function filterByText(int $userId, string $text, string $type): Collection
    {
        if($type === 'personal'){
            return Spent::where('user_id', $userId)
                            ->where('name', 'ilike', '%' . $text . '%')
                            ->get();
        }

        return collect();
    }

    private static function getFilteredSpentsByPeriod(int $userId, string $startDate, string $endDate): Collection
    {
        $informations = Spent::where('user_id', $userId)
                        ->whereBetween('expense_date', [sprintf("'%s'",$startDate), sprintf("'%s'", $endDate)])
                        ->orderBy('expense_date', 'desc')
                        ->get();

        $informations->transform(function ($info) {
            $info->name = trim($info->name);
            $info->price = number_format($info->price, 0, '', '.');
            $info->expense_date = Carbon::parse($info->expense_date)->format('d/m/Y');
            return $info;
        });

        return $informations;
    }

    private static function getAvailableMoneyByPeriod(int $userId, string $startDate, string $endDate): int
    {
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

    private static function getTotalPriceByPeriod(int $userId, string $startDate, string $endDate): int
    {
        return Spent::where('user_id', $userId)
                        ->whereBetween('expense_date', [$startDate, $endDate])
                        ->sum('price');
    }

    private static function getFilteredSellsByPeriod(int $userId, string $startDate, string $endDate): Collection
    {
        $informations = Sell::where('user_id', $userId)
                        ->whereBetween('sell_date', [sprintf("'%s'",$startDate), sprintf("'%s'", $endDate)])
                        ->orderBy('sell_date', 'desc')
                        ->get();

        $informations->transform(function ($info) {
            $info->name = trim($info->name);
            $info->price = number_format($info->price, 0, '', '.');
            $info->sell_date = Carbon::parse($info->sell_date)->format('d/m/Y');

            return $info;
        });

        return $informations;
    }
}

?>
