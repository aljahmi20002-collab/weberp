<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        $business ='';
        $branch   ='';
        if(isSuperadmin()):
            $business = ['required'];
            $branch   = ['required'];
        elseif(business()):
            $branch   = ['required'];
        endif;
        return [
            'business_id'=> $business,
            'branch_id'  => $branch,
            'title'      => ['required'],
            'date'       => ['required'],
        ];
    }
}
