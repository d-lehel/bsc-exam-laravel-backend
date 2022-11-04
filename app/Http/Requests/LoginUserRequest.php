<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'email' => ['required', 'email', 'max:60'],
            'password' => ['required', 'min:6', 'max:127'],
            'grant_type' => ['required', 'in:password'],
            'client_secret' => ['required', 'alpha_num', 'exists:oauth_clients,secret'],
            'client_id' => ['required', 'numeric', 'exists:oauth_clients,id'],
            'remember_me' => ['required']
        ];
    }
}
