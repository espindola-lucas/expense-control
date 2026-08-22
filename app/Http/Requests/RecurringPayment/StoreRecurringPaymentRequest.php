<?php
declare(strict_types=1);

namespace App\Http\Requests\RecurringPayment;

use App\Enums\MovementType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRecurringPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_id' => [
                'required', 'integer',
                Rule::exists('accounts', 'id')->where('user_id', $this->user()->id),
            ],
            'category_id' => [
                'nullable', 'integer',
                Rule::exists('categories', 'id')
                    ->where('user_id', $this->user()->id)
                    ->where('type', $this->input('type')),
            ],
            'name' => 'required|string|max:255',
            'type' => ['required', Rule::in([MovementType::Expense->value, MovementType::Income->value])],
            'amount' => 'required|numeric|min:0',
            'frequency' => ['required', Rule::in(['weekly', 'monthly', 'yearly'])],
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }
}
