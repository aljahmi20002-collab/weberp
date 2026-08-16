<?php

namespace App\Http\Requests\Language;

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
        return [
                'name'            =>   ['required'],
                'code'            =>   ['required','min:1','max:4','unique:languages'],
                'icon_class'      =>   ['required'],
                'text_direction'  =>   ['required']
            ];
    }

    // public function attributes()
    // {
    //     return [
    //         'name.required'          =>   __('validation.required'),
    //         'code.min'               =>   __('validation.min'),
    //         'code.max'               =>   __('validation.max'),
    //         'code.unique'            =>   __('validation.unique'),
    //         'icon_class.required'    =>   __('validation.required'),
    //         'text_direction.required'=>   __('validation.required'),
    //     ];
    // }

}
