<?php

namespace App\Http\Requests\Sale;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Customer\Enums\CustomerType;
use Illuminate\Http\Request;

class StoreSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if (Request::input('customer_type') == CustomerType::EXISTING_CUSTOMER) :
            $customer   = ['required'];
        else :
            $customer   = '';
        endif;

        return [
            'customer_type'      =>    ['required'],
            'customer_id'        =>    $customer,
            'branch_id'          =>    ['required'],
            'tax_id'       =>    ['required'],
            'variation_locations' =>    ['required'],
        ];
    }
}
