<?php
declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Collection;

enum MonthEnum: string
{
    case January = 'Enero';
    case February = 'Febrero';
    case March = 'Marzo';
    case April = 'Abril';
    case May = 'Mayo';
    case June = 'Junio';
    case July = 'Julio';
    case August = 'Agosto';
    case September = 'Septiembre';
    case October = 'Octubre';
    case November = 'Noviembre';
    case December = 'Diciembre';

    public static function getMonths(): Collection
    {
        $months = collect([]);

        foreach (self::cases() as $index => $month) {
            $months->push((object)[
                'name' => $month->value,
                'value' => str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)
            ]);
        }

        return $months;
    }

    public static function getMonthNameByKey(string $key): string
    {
        $index = intval($key) - 1;
        $cases = self::cases();
    
        return $cases[$index]->value ?? '';
    }
}
