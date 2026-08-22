<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Tag\DeleteTagAction;
use App\Actions\Tag\GetTagsAction;
use App\Actions\Tag\StoreTagAction;
use App\Actions\Tag\UpdateTagAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\StoreTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagApiController extends Controller
{
    public function index(Request $request, GetTagsAction $action): JsonResponse
    {
        $tags = $action->execute($request->user()->id);

        return response()->json(TagResource::collection($tags));
    }

    public function store(StoreTagRequest $request, StoreTagAction $action): JsonResponse
    {
        $tag = $action->execute($request->validated(), $request->user()->id);

        return response()->json(new TagResource($tag), 201);
    }

    public function update(UpdateTagRequest $request, Tag $tag, UpdateTagAction $action): JsonResponse
    {
        $this->authorizeOwnership($request, $tag);

        $action->execute($tag, $request->validated());

        return response()->json(new TagResource($tag->fresh()));
    }

    public function destroy(Request $request, Tag $tag, DeleteTagAction $action): JsonResponse
    {
        $this->authorizeOwnership($request, $tag);

        $action->execute($tag);

        return response()->json(null, 204);
    }

    private function authorizeOwnership(Request $request, Tag $tag): void
    {
        if ($tag->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
