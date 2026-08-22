<?php
declare(strict_types=1);

namespace App\Http\Requests\Report;

use App\Enums\MovementType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'account_id' => [
                'nullable', 'integer',
                Rule::exists('accounts', 'id')->where('user_id', $this->user()->id),
            ],
            'group_by' => ['nullable', Rule::in(['day', 'month'])],
            'type' => ['nullable', Rule::in([MovementType::Expense->value, MovementType::Income->value])],
        ];
    }
}
