<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ChangePassword extends FormRequest
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

        return [
            'current_password'     => ['required'],
            'new_password'         => ['required','min:8'],
            'confirm_password'     => ['required','same:new_password'],
        ];
    }

    // public function attributes(){
    //     return [
    //         'current_password.required' => __('validation.required', ['attribute',__('levels.current_password')]),
    //         'new_password.required'     => __('validation.required', ['attribute',__('levels.new_password')]),
    //         'new_password.min'          => __('validation.min',      ['attribute',__('levels.new_password')]),
    //         'confirm_password.required' => __('validation.required', ['attribute',__('levels.confirm_password')]),
    //         'confirm_password.same'     => __('validation.same',     ['attribute',__('levels.confirm_password')]),
    //     ];
    // }
}
