<?php

namespace Modules\Plan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => ['required'],  
            'user_count'    => ['required','numeric'],
            'days_count'    => ['required','numeric'],
            'price'         => ['required','numeric'], 
            'position'      => ['numeric']
        ];
    }

    public function messages()
    {
        return [
            'role_id.required'    => 'The role field is required'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
