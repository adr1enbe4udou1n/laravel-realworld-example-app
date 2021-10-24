<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Article $resource
 */
class SingleArticleResource extends JsonResource
{
    public static $wrap = 'article';

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
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
            'tagList' => new TagsResource($this->resource->tags()->orderBy('name')->get()),
            'favorited' => false,
            'favoritesCount' => 0,
        ];
    }
}
