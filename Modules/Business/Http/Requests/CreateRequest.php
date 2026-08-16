<?php

namespace Modules\Business\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'business_name'     =>  ['required'],
            'start_date'        =>  ['required'],
            'logo'              =>  ['mimes:png,jpg'],
            'currency'          =>  ['required'],
            'country'           =>  ['required'],
            'website'           =>  ['required'],
            'business_phone'    =>  ['required','numeric'],
            'state'             =>  ['required'],
            'city'              =>  ['required'],
            'zip_code'          =>  ['required'],
            'default_profit_percent'=> ['numeric'],
            //owner information
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'      => ['required', 'string', 'min:8', 'confirmed']
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
