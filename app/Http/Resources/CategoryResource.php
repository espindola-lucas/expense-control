<?php
declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'type'       => $this->type->value,
            'icon'       => $this->icon,
            'position'   => (int) $this->position,
            'is_default' => (bool) $this->is_default,
        ];
    }
}
