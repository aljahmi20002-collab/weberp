<?php

namespace App\Http\Requests\Todolist;

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
        if(isSuperadmin()):
            $business = ['required'];
        endif;

        return [
                'business_id'    => $business,
                'project_id'     => ['required'],
                'title'          => ['required'],
                'user_id'        => ['required'],
                'date'           => ['required']
              ];
    }
}
