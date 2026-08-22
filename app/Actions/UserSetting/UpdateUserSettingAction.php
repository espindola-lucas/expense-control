<?php
declare(strict_types=1);

namespace App\Actions\UserSetting;

use App\Models\UserSetting;

class UpdateUserSettingAction
{
    public function execute(UserSetting $userSetting, array $data): void
    {
        $userSetting->update($data);
    }
}
