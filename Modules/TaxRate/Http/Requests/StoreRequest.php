<?php

namespace Modules\TaxRate\Http\Requests;

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
        if(isSuperadmin()):
            $business  =['required'];
        else:
            $business  = '';
        endif;
        return [
                'business_id'  => $business,
                'name'         => ['required'],
                'tax_rate'     => ['required'],
                'position'     => ['numeric']
        ];
    }

    public function messages()
    {
        return [
            'business_id.required' => 'The business field is required'
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
