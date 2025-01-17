<?php
use Carbon\Carbon;

class Helps{
    public static function getNameMonths(){
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

        return $months;
    }

    public static function getDate(){
        $currentDate = Carbon::now()->format('d/m/Y');
        return $currentDate;
    }

    public static function getMonthNameByKey($key){
        $month_name = match($key){
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        };

        return $month_name;
    }

    /**
	* Formats the month number, if it is less than 10, it adds a 0 in front (example. 8 -> 08)
	* @param int $monthInput month number
	* @return int formatted month number
	*/
    public static function formatValue($param) {
        return number_format($param, 0, '', '.');
    }
}

?>
