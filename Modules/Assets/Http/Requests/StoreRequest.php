<?php

namespace Modules\Assets\Http\Requests;

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
            $branch   = ['required'];
        else:
            $branch   = ['required'];
        endif;
        return [ 
            'branch_id'             =>  $branch,
            'asset_category_id'     =>  ['required'],
            'name'                  =>  ['required'],
            'quantity'              =>  ['numeric'],
            'warranty'              =>  ['numeric'],
            'amount'                =>  ['required','numeric']
        ];
    }

    public function attributes()
    {
        return [
            'branch_id'          => 'branch',
            'asset_category_id'  => 'asset category'
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
