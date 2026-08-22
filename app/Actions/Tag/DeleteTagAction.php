<?php
declare(strict_types=1);

namespace App\Actions\Tag;

use App\Models\Tag;

class DeleteTagAction
{
    public function execute(Tag $tag): void
    {
        $tag->delete();
    }
}
