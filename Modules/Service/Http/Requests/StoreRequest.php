<?php

namespace Modules\Service\Http\Requests;

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
        $business  = '';
        if(isSuperadmin()):
            $business = ['required'];
        endif;
        return [
            // 'business_id'   => $business,
            'name'          => ['required'],
            'price'         => ['numeric'],
            'position'      => ['numeric'],
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
