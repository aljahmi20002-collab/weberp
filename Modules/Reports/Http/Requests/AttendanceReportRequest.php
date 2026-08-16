<?php

namespace Modules\Reports\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if(isUser()):
            $user = '';
        else:
            $user = ['required'];
        endif;
        return [
            'date'        => ['required'],
            'employee_id' => $user
        ];
    }
    public function messages()
    {
        return [
            'employee_id.required' => 'The employee field is required.'
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
