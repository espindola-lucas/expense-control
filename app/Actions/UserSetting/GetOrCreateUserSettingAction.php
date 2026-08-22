<?php
declare(strict_types=1);

namespace App\Actions\UserSetting;

use App\Enums\ThemePreference;
use App\Models\UserSetting;

class GetOrCreateUserSettingAction
{
    public function execute(int $userId): UserSetting
    {
        // Defaults are passed explicitly (not left to the migration's DB-level defaults):
        // Eloquent doesn't re-read DB-only defaults into the in-memory model after an insert,
        // so relying on them here would leave $setting->theme null until the next fetch.
        return UserSetting::firstOrCreate(
            ['user_id' => $userId],
            [
                'currency' => 'ARS',
                'timezone' => 'America/Argentina/Buenos_Aires',
                'theme' => ThemePreference::System,
                'date_format' => 'DD/MM/YYYY',
            ],
        );
    }
}
