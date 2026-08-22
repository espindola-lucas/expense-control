<?php

namespace Database\Factories;

use App\Enums\ThemePreference;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserSetting>
 */
class UserSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'currency' => 'ARS',
            'timezone' => 'America/Argentina/Buenos_Aires',
            'theme' => ThemePreference::System,
            'date_format' => 'DD/MM/YYYY',
        ];
    }
}
