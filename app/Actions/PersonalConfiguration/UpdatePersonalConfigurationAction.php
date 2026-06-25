<?php
declare(strict_types=1);

namespace App\Actions\PersonalConfiguration;

use App\Models\PersonalConfiguration;

class UpdatePersonalConfigurationAction
{
    public function execute(PersonalConfiguration $configuration, array $data): void
    {
        $configuration->update($data);
    }
}
