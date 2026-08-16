<?php

namespace Modules\Variation\Http\Requests;

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
            $business_id   = ['required'];
        else:
            $business_id   ='';
        endif;
        return [
            'business_id'    => $business_id,
            'name'           => ['required']
        ];
    }
 
    public function authorize()
    {
        return true;
    }
}
