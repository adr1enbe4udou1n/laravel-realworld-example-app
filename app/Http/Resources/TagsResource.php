<?php

namespace App\Http\Resources;

use App\Models\Tag;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TagsResponse',
    type: 'object',
    properties: [
        new OA\Property(
            property: 'tags',
            type: 'array',
            items: new OA\Items(type: 'string')
        ),
    ]
)]
class TagsResource extends ResourceCollection
{
    public static $wrap = 'tags';

    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(fn (Tag $t) => $t->name);
    }
}
