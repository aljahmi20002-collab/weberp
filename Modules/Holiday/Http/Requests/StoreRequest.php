<?php

namespace Modules\Holiday\Http\Requests;

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
            'name'=> ['required'],
            'from_date'=> ['required','before_or_equal:to_date'],
            'to_date'  => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'from_date.required'=>'The from date fields is required.',
            'to_date.required'  =>'The to date fields is required.'
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
