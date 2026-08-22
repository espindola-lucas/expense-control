<?php
declare(strict_types=1);

namespace App\Http\Requests\UserSetting;

use App\Enums\ThemePreference;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'currency' => 'required|string|size:3',
            'timezone' => 'required|string|timezone',
            'theme' => [
                'required',
                Rule::in([ThemePreference::Light->value, ThemePreference::Dark->value, ThemePreference::System->value]),
            ],
            'date_format' => 'required|string|max:20',
        ];
    }
}
