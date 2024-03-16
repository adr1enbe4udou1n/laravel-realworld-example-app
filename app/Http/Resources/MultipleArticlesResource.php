<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "MultipleArticlesResponse",
    type: "object",
    properties: [
        new OA\Property(
            property: "articles",
            type: "array",
            items: new OA\Items(ref: "#/components/schemas/Article")
        ),
        new OA\Property(
            property: "articlesCount",
            type: "integer"
        )
    ]
)]
class MultipleArticlesResource extends ResourceCollection
{
    public static $wrap = 'articles';

    public $count = 0;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @param  mixed  $count
     */
    public function __construct($resource, $count)
    {
        parent::__construct($resource);

        $this->count = $count;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return SingleArticleResource::collection($this->collection);
    }

    public function with($request)
    {
        return [
            'articlesCount' => $this->count,
        ];
    }
}
