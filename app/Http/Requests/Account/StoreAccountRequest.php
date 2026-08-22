<?php
declare(strict_types=1);

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => [
                'required', 'string', 'max:100',
                Rule::unique('accounts', 'name')->where('user_id', $this->user()->id),
            ],
            'type'            => 'required|string|in:cash,bank,other',
            'initial_balance' => 'nullable|numeric',
        ];
    }
}
