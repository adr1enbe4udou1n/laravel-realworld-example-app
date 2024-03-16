<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "UpdateUser",
    type: "object",
    properties: [
        new OA\Property(
            property: "username",
            type: "string"
        ),
        new OA\Property(
            property: "email",
            type: "string"
        ),
        new OA\Property(
            property: "bio",
            type: "string"
        ),
        new OA\Property(
            property: "image",
            type: "string"
        ),
    ]
)]
#[OA\Schema(
    schema: "UpdateUserRequest",
    type: "object",
    properties: [
        new OA\Property(
            property: "user",
            ref: "#/components/schemas/UpdateUser",
        ),
    ]
)]
class UpdateUserRequest extends FormRequest
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
        /** @var User */
        $user = Auth::user();

        return [
            'email' => ['sometimes', 'required', 'email', Rule::unique('users')->ignoreModel($user)],
            'username' => ['sometimes', 'required'],
        ];
    }

    public function updatedUser()
    {
        /** @var User */
        $user = Auth::user();

        if ($this->input('user.username')) {
            $user->name = $this->input('user.username');
        }

        return $user->fill($this->input('user'));
    }

    public function validationData()
    {
        return Arr::wrap($this->input('user'));
    }
}
