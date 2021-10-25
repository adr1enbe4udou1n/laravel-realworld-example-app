<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class NewUserRequest extends FormRequest
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
            'email' => ['required', 'email', Rule::unique('users')],
            'username' => ['required'],
            'password' => ['required', 'min:8'],
        ];
    }

    public function newUser()
    {
        return new User([
            'name' => $this->input('user.username'),
            'email' => $this->input('user.email'),
            'password' => Hash::make($this->input('user.password')),
        ]);
    }

    public function validationData()
    {
        return Arr::wrap($this->input('user'));
    }
}
