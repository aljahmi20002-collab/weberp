<?php

namespace Modules\Purchase\Http\Requests\PurchaseReturn;

use Illuminate\Foundation\Http\FormRequest;

class AddPaymentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
       
        return [
            'payment_method' => ['required','numeric'],
            'amount'         => ['required','numeric'],
            'paid_date'      => ['required']
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
