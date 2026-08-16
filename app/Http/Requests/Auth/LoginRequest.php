<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {

        if(settings('recaptcha_status') == 1):
            $recaptcha  = 'required|string';
        else:
            $recaptcha  = '';
        endif;
        return [
            'email'                => ['required'],
            'password'             => ['required'],
            'g-recaptcha-response' => $recaptcha
        ];

    }
    public function messages()
    {
        return [
            'g-recaptcha-response.required'=>'Please verify that you are not a robot.'
        ];
    }


}
