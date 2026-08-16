<?php

namespace Modules\Sell\Http\Requests\Sale;

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
        if(\Request::input('customer_type') == CustomerType::EXISTING_CUSTOMER):
            $customer   = ['required'];
        else:
            $customer   ='';
        endif;

        if(isUser()):
            $branch   = '';
        else:
            $branch  = ['required'];
        endif;
        return [
            'customer_type'      =>    ['required'],
            'customer_id'        =>    $customer,
            'branch_id'          =>    $branch,
            'tax_id'             =>    ['required'],
            'variation_locations'=>    ['required'], 
            'total_price'        => ['required','numeric'],
            'total_tax_amount'   => ['required','numeric'],
            'total_sell_price'   => ['required','numeric'],
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
