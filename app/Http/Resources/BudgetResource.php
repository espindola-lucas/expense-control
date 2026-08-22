<?php
declare(strict_types=1);

namespace App\Http\Resources;

use App\Actions\Budget\GetBudgetStatusAction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BudgetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $status = app(GetBudgetStatusAction::class)->execute($this->resource);

        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'category_name' => $this->category?->name,
            'month' => $this->month->toDateString(),
            'amount' => (float) $this->amount,
            'spent' => (float) $status['spent'],
            'percentageUsed' => $status['percentageUsed'],
            'level' => $status['level'],
        ];
    }
}
