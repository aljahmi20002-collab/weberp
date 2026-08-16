<?php

namespace Modules\Subscription\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanchangeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'business_id'=> ['required'],
            'plan_id'    => ['required']
        ];
    }

    public function messages()
    {
        return [
            'business_id.required' => 'The business field is required',
            'plan_id.required'     => 'The plan field is required'
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
