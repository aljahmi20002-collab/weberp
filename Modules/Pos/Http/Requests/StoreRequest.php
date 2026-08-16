<?php

namespace Modules\Pos\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Customer\Enums\CustomerType;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    { 
      
        if(isUser()):
            $branch   = '';
        else:
            $branch  = ['required'];
        endif;
        return [
            'customer_id'           =>    ['required'], 
            'branch_id'             =>    $branch,
            'tax_id'                =>    ['required'],
            'variation_locations'   =>    ['required'], 
            'total_price'           =>    ['required','numeric'],
            'total_tax_amount'      =>    ['required','numeric'],
            'total_sell_price'      =>    ['required','numeric'],
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
