<?php

namespace Modules\DutySchedule\Http\Requests;

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
            'role_id'     => ['required','unique:duty_schedules,role_id'],
            'start_time'  => ['required'],
            'end_time'    => ['required'],
        ];
    }

    public function messages(){
        return [
            'role_id.required' => __('the_role_field_is_required')
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
