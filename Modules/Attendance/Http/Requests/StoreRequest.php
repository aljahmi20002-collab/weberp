<?php

namespace Modules\Attendance\Http\Requests;

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
        if(isUser()):
            $user     = ''; 
        else:
            $user     = ['required']; 
        endif;
        return [
            'employee_id'   => $user, 
            'date'          => ['required'],
            'check_in'      => ['required'],  
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
