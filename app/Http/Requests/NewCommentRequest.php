<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'NewComment',
    type: 'object',
    properties: [
        new OA\Property(
            property: 'body',
            type: 'string'
        ),
    ]
)]
#[OA\Schema(
    schema: 'NewCommentRequest',
    type: 'object',
    properties: [
        new OA\Property(
            property: 'comment',
            ref: '#/components/schemas/NewComment',
        ),
    ]
)]
class NewCommentRequest extends FormRequest
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
            'body' => 'required',
        ];
    }

    public function validationData()
    {
        return Arr::wrap($this->input('comment'));
    }
}
