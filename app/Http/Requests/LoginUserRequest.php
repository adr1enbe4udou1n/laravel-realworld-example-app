<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "LoginUser",
    type: "object",
    properties: [
        new OA\Property(
            property: "email",
            type: "string"
        ),
        new OA\Property(
            property: "password",
            type: "string"
        ),
    ]
)]
#[OA\Schema(
    schema: "LoginUserRequest",
    type: "object",
    properties: [
        new OA\Property(
            property: "user",
            ref: "#/components/schemas/LoginUser",
        ),
    ]
)]
class LoginUserRequest extends FormRequest
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
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function validationData()
    {
        return Arr::wrap($this->input('user'));
    }
}
