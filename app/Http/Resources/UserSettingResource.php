<?php
declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'currency' => $this->currency,
            'timezone' => $this->timezone,
            'theme' => $this->theme->value,
            'date_format' => $this->date_format,
        ];
    }
}
