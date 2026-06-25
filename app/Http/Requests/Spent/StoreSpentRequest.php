<?php
declare(strict_types=1);

namespace App\Http\Requests\Spent;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'expense_date' => 'required|date',
            'spentName'    => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
        ];
    }
}
