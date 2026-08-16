<?php

namespace App\Imports;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel; 
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Modules\Category\Entities\Category;

class CategoryImport implements ToModel,WithHeadingRow,WithValidation,SkipsEmptyRows
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
        $request['parent_id']   = $row['category_id']; 

        return $this->CategoryStore($request);
    }
  
    public function CategoryStore($request){  
        $category               = new Category();
        $category->business_id  = business_id();
        $category->name         = $request->name;
        $category->description  = $request->description;
        if($request->parent_id && !blank($request->parent_id)):
            $category->parent_id = $request->parent_id;
        endif;  
        $category->position      = $request->position; 
        $category->save();
    } 

 

    public function rules(): array
    {  
          
        return [
                'name'     => ['required'],
                'position' => ['numeric'], 
            ];
    }

}
