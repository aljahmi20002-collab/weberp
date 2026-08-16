<?php

namespace Modules\Income\Http\Requests;

use App\Enums\AccountHead;
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
            'account_head'   => ['required'],
            'from_branch'    => ['required'],
            'from_account'   => ['required'],
            'to_account'     => ['required'],
            'amount'         => ['required','numeric'],
            'document'       => ['mimes:png,jpg,pdf']
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
