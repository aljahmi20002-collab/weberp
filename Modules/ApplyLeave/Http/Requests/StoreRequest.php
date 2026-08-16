<?php

namespace Modules\ApplyLeave\Http\Requests;

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
        if(isSuperadmin() || business()):
            $employee_id = ['required'];
        else:
            $employee_id = '';
        endif;
        return [
            'employee_id'       => $employee_id,
            'leave_assign_id'   => ['required'],
            'manager'           => ['required'],
            'leave_from'        => ['required','before_or_equal:leave_to'],
            'leave_to'          => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'leave_assign_id.required' => __('the_leave_type_field_is_required'),
            'employee_id.required' => __('the_employee_field_is_required')
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
