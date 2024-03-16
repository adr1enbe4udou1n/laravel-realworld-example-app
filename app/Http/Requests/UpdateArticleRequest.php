<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "UpdateArticle",
    type: "object",
    properties: [
        new OA\Property(
            property: "title",
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
    ]
)]
#[OA\Schema(
    schema: "UpdateArticleRequest",
    type: "object",
    properties: [
        new OA\Property(
            property: "article",
            ref: "#/components/schemas/UpdateArticle",
        ),
    ]
)]
class UpdateArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['sometimes', 'required'],
            'description' => ['sometimes', 'required'],
            'body' => ['sometimes', 'required'],
        ];
    }

    public function validationData()
    {
        return Arr::wrap($this->input('article'));
    }
}
