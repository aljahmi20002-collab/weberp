<?php

namespace App\Http\Requests\CrudGenerator;

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
            'model_name'        => ['required','string'],
            'module_icon_class' => ['required','numeric'],
            'paginate'          => ['required','numeric']
        ];
    }
}
