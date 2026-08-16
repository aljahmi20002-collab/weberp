<?php

namespace Modules\Account\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Account\Enums\PaymentGateway;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if(\Request::input('payment_gateway')== PaymentGateway::BANK):
            $bank_name     = ['required'];
            $holder_name   = ['required'];
            $account_no    = ['required'];
            $branch_name   = ['required'];
            $mobile        = '';
            $number_type   = '';
        elseif(\Request::input('payment_gateway') == PaymentGateway::MOBILE):
            $bank_name     = '';
            $holder_name   = ['required'];
            $account_no    = '';
            $branch_name   = '';
            $mobile        = ['required'];
            $number_type   = ['required'];
        elseif(\Request::input('payment_gateway')== PaymentGateway::CASH):
            $bank_name     = '';
            $holder_name   = '';
            $account_no    = '';
            $branch_name   = '';
            $mobile        = '';
            $number_type   = '';
        endif;
        if(business()):
            $balance  = ['numeric'];
        else:
            $balance  = '';
        endif;
        return [ 
            'payment_gateway'   => ['required'],
            'bank_name'         => $bank_name,
            'holder_name'       => $holder_name,
            'account_no'        => $account_no,
            'branch_name'       => $branch_name,
            'mobile'            => $mobile,
            'number_type'       => $number_type,
            'balance'           => $balance
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
