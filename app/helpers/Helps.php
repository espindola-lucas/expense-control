<?php
namespace App\Helpers;
use Carbon\Carbon;
use App\Enums\MonthEnum;

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
}

?>
