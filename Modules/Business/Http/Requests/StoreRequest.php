<?php

namespace Modules\Business\Http\Requests;

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
            'business_name'     =>  ['required'],
            'start_date'        =>  ['required'],
            'logo'              =>  ['mimes:png,jpg'],
            'currency'          =>  ['required'],
            'default_profit_percent'=> ['numeric'],
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
