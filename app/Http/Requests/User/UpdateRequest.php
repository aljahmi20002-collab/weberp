<?php

namespace App\Http\Requests\User;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateRequest extends FormRequest
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
         
        $phone   ='';
        if(Request::input('phone')):
            $phone = ['numeric'];
        endif;
        $password      = [];
        if(Request::input('password')):
            $password  = ['required','string','min:8','confirmed'];
        endif;

        if(isSuperadmin() && Request::input('user_type') == UserType::ADMIN || Request::input('user_type') == UserType::SUPERADMIN):
            $branch='';
        elseif(Request::input('user_type' ) == UserType::SUPERADMIN) :
            $branch='';
        else:
            $branch=[''];
        endif;

        if(Auth::user()->user_type == UserType::USER || Auth::user()->user_type == UserType::ADMIN):
            $business='';
        elseif(Request::input('user_type' ) == UserType::SUPERADMIN) :
            $business='';
        else:
            $business=[''];
        endif;
       
        return [
            'name'          => ['required','min:4'],
            'email'         => 'required|string|unique:users,email,'.Request::input('id'),
            'phone'         => $phone,
            'user_type'     => ['required'],
            'business_id'   => $business,
            'branch_id'     => $branch,
            // 'role'          => ['required','numeric'],
            'password'      => $password,
        ];
    }

    public function messages()
    {
        return [
            'business_id.required'=> 'The business field is required.',
            'branch_id.required'  => 'The business field is required.'
        ];
    }

}
