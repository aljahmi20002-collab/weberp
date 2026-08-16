<?php

namespace Modules\BusinessSettings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
