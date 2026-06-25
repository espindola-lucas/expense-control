<?php
declare(strict_types=1);

namespace App\Actions\PersonalConfiguration;

use App\Models\PersonalConfiguration;
use Illuminate\Support\Collection;

class GetPersonalConfigurationsAction
{
    public function execute(int $userId): Collection
    {
        return PersonalConfiguration::where('user_id', $userId)
                                    ->orderBy('end_counting', 'desc')
                                    ->get();
    }
}
