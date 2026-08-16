<?php

namespace Modules\Warranties\Http\Requests;

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
            $business  = ['required'];
        else:
            $business  = '';
        endif;
        return [
            'business_id'    => $business,
            'name'           => ['required'], 
            'duration'       => ['numeric'],
            'duration_type'  => ['numeric']
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
