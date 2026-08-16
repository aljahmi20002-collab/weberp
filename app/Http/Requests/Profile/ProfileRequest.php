<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProfileRequest extends FormRequest
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


        if(Request::input('name') || Request::input('profile_name')){
            return [
                'name'    => ['required','min:4']
            ];
        }else{
            $phone = '';
            if(Request::input('phone')):
                $phone = ['numeric'];
            endif;
            return [ 
                 'phone'          => $phone,
            ];
        }
    }

    // public function attributes(){

    //     return [
    //         'name.required'  => __('validation.required'),
    //         'name.min'       => __('validation.min'),
    //         'gender.required'=> __('auth.gender_required')
    //     ];
    // }

    public function messages(){
        return [
            'gender.required' => __('gender_required')
        ];
    }
}
