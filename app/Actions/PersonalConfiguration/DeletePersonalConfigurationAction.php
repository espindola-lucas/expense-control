<?php
declare(strict_types=1);

namespace App\Actions\PersonalConfiguration;

use App\Models\PersonalConfiguration;

class DeletePersonalConfigurationAction
{
    public function execute(PersonalConfiguration $configuration): void
    {
        $configuration->delete();
    }
}
