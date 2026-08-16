<?php

namespace Modules\StockTransfer\Http\Requests;

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
        
 
        if(business()):
            $from_branch = ['required'];
        else:
            $from_branch = '';
        endif;
        return [
            'branch_id'      => $from_branch,
            'to_branch'      => ['required','different:branch_id'], 
            'shipping_charge'=> ['required','numeric'],
            'total_amount'   => ['required','numeric']
        ];
    }
    public function messages()
    {
        return [
            'branch_id.required' =>'The From Branch field is required.', 
            'to_branch.required' =>'The To Branch field is required.',
            'to_branch.different' =>'The to branch and from branch must be different..'
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
