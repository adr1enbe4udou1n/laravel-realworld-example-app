<?php

namespace App\Http\Requests;

use App\Models\User;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
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
            'email' => ['required', 'email', Rule::unique('users')->ignoreModel($user)],
            'username' => ['required'],
        ];
    }

    public function updatedUser()
    {
        /** @var User */
        $user = Auth::user();

        return $user->fill([
            'name' => $this->input('user.username'),
            'email' => $this->input('user.email'),
            'bio' => $this->input('user.bio'),
            'image' => $this->input('user.image'),
        ]);
    }

    public function validationData()
    {
        return Arr::wrap($this->input('user'));
    }
}
