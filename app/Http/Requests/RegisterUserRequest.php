<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
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
            'name' => ['required', 'min:6', 'max:50', 'regex:/[A-z]+/'],
            'email' => ['required', 'unique:users', 'email:rfc,dns', 'max:256'],
            'password' => ['required', 'confirmed', 'max:256',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
            ]
        ];
    }

    // hogyan tudnam ezt beallitani?

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name is required!',
            'name.min' => 'Name is too short!',
            'name.max' => 'Name is too long!',
            'name.regex' => 'Name cannot contain special characters!',
            'email.required' => 'Email is required!',
            'email.max' => 'Email is too long!',
            'email.email' => 'Not valid email format!',
            'password.min' => 'Password is too short!',
            'password.required' => 'Password is required!',
        ];
    }
}
