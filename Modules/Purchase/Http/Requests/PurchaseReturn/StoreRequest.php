<?php

namespace Modules\Purchase\Http\Requests\PurchaseReturn;

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
            'supplier_id'         => ['required'],  
            'tax_id'              => ['required'],
            'variation_locations' => ['required'],

            
            'total_price'         => ['required','numeric'],
            'total_tax_amount'    => ['required','numeric'],
            'total_buy_cost'      => ['required','numeric'],
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
    public function messages()
    {
        return [
            'supplier_id.required'       => 'The supplier field is required.', 
            'tax_id.required'            => 'The tax field is required.',
            'variation_locations.required'=> 'Add purchase variation.'
        ];
    }
}
