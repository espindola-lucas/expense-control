<?php
declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'     => (string) $this->id,
            'name'   => trim($this->name),
            'amount' => (float) $this->price,
            'date'   => $this->expense_date,
        ];
    }
}
