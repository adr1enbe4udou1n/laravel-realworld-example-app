<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'NewArticle',
    type: 'object',
    properties: [
        new OA\Property(
            property: 'title',
            type: 'string'
        ),
        new OA\Property(
            property: 'description',
            type: 'string'
        ),
        new OA\Property(
            property: 'body',
            type: 'string'
        ),
        new OA\Property(
            property: 'tagList',
            type: 'array',
            items: new OA\Items(type: 'string'),
        ),
    ]
)]
#[OA\Schema(
    schema: 'NewArticleRequest',
    type: 'object',
    properties: [
        new OA\Property(
            property: 'article',
            ref: '#/components/schemas/NewArticle',
        ),
    ]
)]
class NewArticleRequest extends FormRequest
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
            'title' => ['required'],
            'description' => ['required'],
            'body' => ['required'],
            'tagList.*' => ['string'],
            'slug' => [Rule::unique('articles')],
        ];
    }

    public function validationData()
    {
        return Arr::wrap($this->input('article'));
    }

    protected function prepareForValidation()
    {
        $this->replace([
            'article' => $this->input('article') + ['slug' => Str::slug($this->article['title'])],
        ]);
    }
}
