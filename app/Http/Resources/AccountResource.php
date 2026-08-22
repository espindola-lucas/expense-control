<?php
declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'type'            => $this->type->value,
            'initial_balance' => (float) $this->initial_balance,
            'balance'         => (float) $this->balance,
            'is_archived'     => (bool) $this->is_archived,
            'position'        => (int) $this->position,
        ];
    }
}
