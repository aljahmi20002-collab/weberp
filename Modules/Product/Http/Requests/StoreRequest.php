<?php

namespace Modules\Product\Http\Requests;

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
        if(!isUser()):
            $branch_id = ['required'];
        else:   
            $branch_id = '';
        endif;
        if(!blank(\Request::input('enable_stock'))):
            $alert_quantity   = ['required'];
        else:
            $alert_quantity   = '';
        endif; 
        return [
            'business_id'             => $business_id, 
            'name'                    => ['required'],
            'barcode_type'            => ['required'],
            'unit_id'                 => ['required','numeric'],
            'brand_id'                => ['required','numeric'],
            'warranty_id'             => ['required','numeric'],
            'category_id'             => ['required','numeric'],
            'subcategory_id'          => ['required','numeric'],
            'branches'                => $branch_id,
            'variation_id'            => ['required'],
            'variation_values'        => ['required'],
            'alert_quantity'          => $alert_quantity,
            'product_image'           => ['mimes:png,jpg,avif,gif,webp'],
            'quantity'                => ['numeric'],
            'default_purchase_price'  => ['required','numeric'],
            'profit_percent'          => ['required','numeric'],
            'selling_price'           => ['required','numeric'],
            'tax_id'                  => ['required','numeric']
        ];
    }

    public function messages()
    {
        return [
            'business_id.required'   => 'The business field is required.',
            'unit_id.required'       => 'The unit field is required.',
            'brand_id.required'      => 'The brand field is required.',
            'category_id.required'   => 'The category field is required.',
            'subcategory_id.required'=> 'The subcategory field is required.',
            'tax_id.required'        => 'The tax field is required.',
            'branches.required'      => 'The branch field is required.',
            'variation_id.required'  => 'The variation field is required.'
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
