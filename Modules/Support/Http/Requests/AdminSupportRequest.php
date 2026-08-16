<?php

namespace Modules\Support\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Support\Traits\ValidationTrait;
class AdminSupportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    use ValidationTrait;
    public function rules()
    {
 
        return $this->AdminSupportValidation(\Request::input('reply'));

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

    public function attributes()
    {
        return [
            'user_id'       => 'user',
            'service_id'    => 'service',
            'department_id' => 'department'
        ];
    }
}
