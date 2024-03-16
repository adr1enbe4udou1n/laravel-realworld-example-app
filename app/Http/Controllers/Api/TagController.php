<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagsResource;
use App\Models\Tag;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;
use OpenApi\Attributes as OA;

#[Prefix('tags')]
class TagController extends Controller
{
    /**
     * Get tags.
     *
     * Get tags. Auth not required
     */
    #[Get('/')]
    #[OA\Get(path: '/tags', operationId: 'GetTags', tags: ['Tags'])]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(ref: TagsResource::class)
    )]
    public function list(): TagsResource
    {
        return new TagsResource(Tag::orderBy('name')->get());
    }
}
