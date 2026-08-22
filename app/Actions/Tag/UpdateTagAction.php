<?php
declare(strict_types=1);

namespace App\Actions\Tag;

use App\Models\Tag;

class UpdateTagAction
{
    public function execute(Tag $tag, array $data): void
    {
        $tag->update($data);
    }
}
