<?php
declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\MonthEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonalConfigurationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                       => $this->id,
            'start_counting'           => $this->start_counting,
            'end_counting'             => $this->end_counting,
            'available_money'          => (float) $this->available_money,
            'month_available_money'    => $this->month_available_money,
            'month_name'               => MonthEnum::getMonthNameByKey($this->month_available_money),
            'expense_percentage_limit' => (int) $this->expense_percentage_limit,
        ];
    }
}
