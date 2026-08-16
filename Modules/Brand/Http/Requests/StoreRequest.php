<?php

namespace Modules\Brand\Http\Requests;

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
            'business_id'  => $business,
            'name'         => ['required'],
            'position'     => ['numeric'],
            'logo'         => ['mimes:png,jpg'],
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
