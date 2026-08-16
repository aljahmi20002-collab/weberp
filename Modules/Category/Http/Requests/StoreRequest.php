<?php

namespace Modules\Category\Http\Requests;

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
            $business_id = ['required'];
        else:
            $business_id = '';
        endif;
        return [
            'business_id'=>$business_id,
            'name'       => ['required'],
            'image'      => ['mimes:png,jpg'],
            'position'   => ['numeric']
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
