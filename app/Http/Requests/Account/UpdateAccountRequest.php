<?php
declare(strict_types=1);

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
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
                Rule::unique('accounts', 'name')->where('user_id', $this->user()->id)->ignore($this->route('account')),
            ],
            'type'            => 'required|string|in:cash,bank,other',
            'initial_balance' => 'nullable|numeric',
            'is_archived'     => 'nullable|boolean',
        ];
    }
}
