<?php

namespace Modules\BusinessSettings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarcodeSettingStoreRequest extends FormRequest
{
   
    public function rules()
    {
        return [
            'paper_width'               => ['required'],
            'paper_width_type'          => ['required'],
            'paper_height'              => ['required'],
            'paper_height_type'         => ['required'],
            'label_width'               => ['required'],
            'label_width_type'          => ['required'],
            'label_height'              => ['required'],
            'label_height_type'         => ['required'],
            'label_in_per_row'          => ['required']
        ];
    }
 
    public function authorize()
    {
        return true;
    }
}
