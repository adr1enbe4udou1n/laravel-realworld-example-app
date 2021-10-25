<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @param Article[] $resource
 */
class MultipleArticlesResource extends ResourceCollection
{
    public static $wrap = 'articles';

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return SingleArticleResource::collection($this->resource);
    }
}
