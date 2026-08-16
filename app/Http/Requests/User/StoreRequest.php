<?php

namespace App\Http\Requests\User;

use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
  

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
      
        $phone   ='';
        if(Request::input('phone')):
            $phone = ['numeric'];
        endif;

        if(isSuperadmin() && Request::input('user_type') == UserType::ADMIN || Request::input('user_type') == UserType::SUPERADMIN):
            $branch='';
        elseif(Request::input('user_type' ) == UserType::SUPERADMIN) :
            $branch='';
        else:
            $branch=['required'];
        endif;
        if(Auth::user()->user_type == UserType::USER || Auth::user()->user_type == UserType::ADMIN):
            $business='';
        elseif(Request::input('user_type' ) == UserType::SUPERADMIN) :
            $business='';
        else:
            $business=['required'];
        endif;
        
        return [
            'name'          => ['required','min:4'],
            'email'         => ['required','string','unique:users'],
            'phone'         => $phone,
            'user_type'     => ['required'],
            'business_id'   => $business,
            'branch_id'     => $branch,
            // 'role'       => ['required','numeric'],
            'password'      => ['required','string','min:8','confirmed'],
        ];

    }
    public function messages()
    {
        return [
            'business_id.required'=> 'The business field is required.',
            'branch_id.required'  => 'The branch field is required.'
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
