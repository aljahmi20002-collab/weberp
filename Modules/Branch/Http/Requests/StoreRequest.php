<?php

namespace Modules\Branch\Http\Requests;

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
            'business_id'       =>  ['required'],
            'branch_name'       =>  ['required'],
            'email'             =>  ['required'],
            'website'           =>  ['required'],
            'business_phone'    =>  ['required','numeric'],
            'country'           =>  ['required'],
            'state'             =>  ['required'],
            'city'              =>  ['required'],
            'zip_code'          =>  ['required'],
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
