<?php

namespace Modules\LeaveAssign\Http\Requests;

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
            'role_id'=>['required'],
            'type_id'=>['required'],
            'days'   => ['required','numeric'],
        ];
    }

    public function messages()
    {
        return [
            'role_id.required'=>'The role field is required.',
            'type_id.required'=>'The leave type field is required.'
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
