<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagsResource;
use App\OpenApi\Responses\TagsResponse;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;
use Vyuldashev\LaravelOpenApi\Attributes\Operation;
use Vyuldashev\LaravelOpenApi\Attributes\PathItem;
use Vyuldashev\LaravelOpenApi\Attributes\Response;

#[Prefix('tags')]
#[PathItem]
class TagController extends Controller
{
    /**
     * Get tags.
     *
     * Get tags. Auth not required
     */
    #[Get('/')]
    #[Operation(tags: ['Tags'])]
    #[Response(factory: TagsResponse::class, statusCode: 200)]
    public function list(): TagsResource
    {
        return new TagsResource(null);
    }
}
