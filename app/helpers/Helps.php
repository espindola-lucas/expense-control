<?php
namespace App\Helpers;
use Carbon\Carbon;
use App\Enums\MonthEnum;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use function PHPUnit\Framework\throwException;

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
}

?>
