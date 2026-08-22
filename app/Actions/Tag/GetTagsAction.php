<?php
declare(strict_types=1);

namespace App\Actions\Tag;

use App\Models\Tag;
use Illuminate\Support\Collection;

class GetTagsAction
{
    public function execute(int $userId): Collection
    {
        return Tag::where('user_id', $userId)
            ->orderBy('name')
            ->get();
    }
}
