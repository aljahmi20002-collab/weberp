<?php

namespace App\Http\Requests\CrudGenerator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title'            => ['required', 'string', 'max:255'],
            'model_name'       => ['required', 'string', 'regex:/^[A-Za-z][A-Za-z0-9_]{0,60}$/'],
            'icon_class'       => ['nullable', 'string', 'max:100'],
            'module_icon_class'=> ['nullable', 'integer'],
            'paginate'         => ['nullable', 'integer'],

            'field'               => ['required', 'array', 'min:1'],
            'field.*.field_name'  => ['required', 'string', 'regex:/^[a-z_][a-z0-9_]{0,60}$/'],
            'field.*.db_type'     => [
                'required', 'string',
                Rule::in([
                    'string', 'char', 'text', 'mediumText', 'longText', 'integer',
                    'bigInteger', 'smallInteger', 'tinyInteger', 'float', 'double',
                    'decimal', 'boolean', 'date', 'dateTime', 'dateTimeTz', 'time',
                    'timestamp', 'timestampTz', 'binary', 'json', 'jsonb', 'uuid',
                ]),
            ],
        ];
    }
}
