<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagsResource;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;
use Vyuldashev\LaravelOpenApi\Attributes\Operation;
use Vyuldashev\LaravelOpenApi\Attributes\PathItem;

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
    public function list(): TagsResource
    {
        return new TagsResource(null);
    }
}
