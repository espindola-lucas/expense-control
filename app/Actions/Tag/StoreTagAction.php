<?php
declare(strict_types=1);

namespace App\Actions\Tag;

use App\Models\Tag;

class StoreTagAction
{
    public function execute(array $data, int $userId): Tag
    {
        return Tag::create([
            'user_id' => $userId,
            'name'    => $data['name'],
        ]);
    }
}
