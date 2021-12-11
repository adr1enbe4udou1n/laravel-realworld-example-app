<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
