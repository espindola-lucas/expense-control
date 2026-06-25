<?php
declare(strict_types=1);

namespace App\Http\Requests\PersonalConfiguration;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonalConfigurationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_counting'           => 'required|date',
            'end_counting'             => 'required|date|after_or_equal:start_counting',
            'available_money'          => 'required|numeric|min:0',
            'month_available_money'    => 'required|string|size:2',
            'expense_percentage_limit' => 'required|numeric|min:0|max:100',
        ];
    }
}
