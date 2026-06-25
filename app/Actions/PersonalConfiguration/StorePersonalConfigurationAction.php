<?php
declare(strict_types=1);

namespace App\Actions\PersonalConfiguration;

use App\Models\PersonalConfiguration;

class StorePersonalConfigurationAction
{
    public function execute(array $data, int $userId): PersonalConfiguration
    {
        return PersonalConfiguration::create([
            'start_counting'           => $data['start_counting'],
            'end_counting'             => $data['end_counting'],
            'available_money'          => $data['available_money'],
            'month_available_money'    => $data['month_available_money'],
            'expense_percentage_limit' => $data['expense_percentage_limit'],
            'user_id'                  => $userId,
        ]);
    }
}
