<?php

namespace App\Http\Resources;

use App\Models\Tag;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @param Tag[] $resource
 */
class TagsResource extends ResourceCollection
{
    public static $wrap = 'tags';

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect($this->resource)->map(fn (Tag $t) => $t->name);
    }
}
