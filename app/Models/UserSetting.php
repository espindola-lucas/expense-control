<?php

namespace App\Models;

use App\Enums\ThemePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'currency', 'timezone', 'theme', 'date_format'];

    protected function casts(): array
    {
        return [
            'theme' => ThemePreference::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
