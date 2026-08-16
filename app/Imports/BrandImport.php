<?php

namespace App\Imports;
 
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToModel; 
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Modules\Brand\Entities\Brand; 

class BrandImport implements ToModel,WithHeadingRow,WithValidation,SkipsEmptyRows
{
    use Importable;
    /**
    * @param array $row
    */ 
 
   
    public function model(array $row)
    {  
        $request                = new Request();
        $request['name']        = $row['name'];   
        $request['description'] = $row['description'];
        $request['position']    = $row['position'];  

        return $this->BrandStore($request);
    }
  
    public function BrandStore($request){  
        $brand               = new Brand();
        $brand->business_id  = business_id();
        $brand->name         = $request->name;
        $brand->description  = $request->description; 
        $brand->position     = $request->position; 
        $brand->save();
    }

    public function rules(): array
    {
        return [
                'name'     => ['required'],
                'position' => ['numeric'], 
            ];
    }

}
