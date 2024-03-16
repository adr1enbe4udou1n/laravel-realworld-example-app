<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MultipleCommentsResponse',
    type: 'object',
    properties: [
        new OA\Property(
            property: 'comments',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/Comment')
        ),
    ]
)]
class MultipleCommentsResource extends ResourceCollection
{
    public static $wrap = 'comments';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return SingleCommentResource::collection($this->collection);
    }
}
