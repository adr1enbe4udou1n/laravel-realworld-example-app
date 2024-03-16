<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

/**
 * @property Article $resource
 */
#[OA\Schema(
    schema: "SingleArticleResponse",
    type: "object",
    properties: [
        new OA\Property(
            property: "article",
            ref: "#/components/schemas/Article"
        )
    ]
)]
#[OA\Schema(
    schema: "Article",
    type: "object",
    properties: [
        new OA\Property(
            property: "title",
            type: "string"
        ),
        new OA\Property(
            property: "slug",
            type: "string"
        ),
        new OA\Property(
            property: "description",
            type: "string"
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
        ),
        new OA\Property(
            property: "tagList",
            type: "array",
            items: new OA\Items(type: "string")
        ),
        new OA\Property(
            property: "favorited",
            type: "boolean",
            default: false
        ),
        new OA\Property(
            property: "favoritesCount",
            type: "integer"
        )
    ]
)]
class SingleArticleResource extends JsonResource
{
    public static $wrap = 'article';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'description' => $this->resource->description,
            'body' => $this->resource->body,
            'createdAt' => $this->resource->created_at,
            'updatedAt' => $this->resource->updated_at,
            'author' => new ProfileResource($this->resource->author),
            'tagList' => new TagsResource($this->resource->tags->sortBy('name')),
            'favorited' => Auth::check() ? $this->resource->favoritedBy->contains('id', Auth::id()) : false,
            'favoritesCount' => $this->resource->favoritedBy->count(),
        ];
    }
}
