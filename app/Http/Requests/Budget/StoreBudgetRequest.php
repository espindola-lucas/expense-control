<?php
declare(strict_types=1);

namespace App\Http\Requests\Budget;

use App\Enums\CategoryType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')
                    ->where('user_id', $this->user()->id)
                    ->where('type', CategoryType::Expense->value),
                Rule::unique('budgets')->where(function ($query) {
                    $query->where('user_id', $this->user()->id)->where('month', $this->input('month'));
                }),
            ],
            'month' => 'required|date',
            'amount' => 'required|numeric|min:0',
        ];
    }
}
