<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @property Comment $resource
 */
#[OA\Schema(
    schema: "Comment",
    type: "object",
    properties: [
        new OA\Property(
            property: "id",
            type: "integer"
        ),
        new OA\Property(
            property: "body",
            type: "string"
        ),
        new OA\Property(
            property: "createdAt",
            type: "string",
            format: "date-time"
        ),
        new OA\Property(
            property: "updatedAt",
            type: "string",
            format: "date-time"
        ),
        new OA\Property(
            property: "author",
            ref: "#/components/schemas/Profile"
        )
    ]
)]
#[OA\Schema(
    schema: "SingleCommentResponse",
    type: "object",
    properties: [
        new OA\Property(
            property: "comment",
            ref: "#/components/schemas/Comment"
        )
    ]
)]
class SingleCommentResource extends JsonResource
{
    public static $wrap = 'comment';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'body' => $this->resource->body,
            'createdAt' => $this->resource->created_at,
            'updatedAt' => $this->resource->updated_at,
            'author' => new ProfileResource($this->resource->author),
        ];
    }
}
