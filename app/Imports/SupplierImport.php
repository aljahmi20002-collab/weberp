<?php

namespace App\Imports;
 
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToModel; 
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Modules\Supplier\Entities\Supplier;

class SupplierImport implements ToModel,WithHeadingRow,WithValidation,SkipsEmptyRows
{
    use Importable;
    /**
    * @param array $row
    */ 
  
    public function model(array $row)
    {  
        $request                       = new Request();
        $request['name']               = $row['name'];   
        $request['company_name']       = $row['company_name'];   
        $request['phone']              = $row['phone'];
        $request['email']              = $row['email'];  
        $request['address']            = $row['address'];  
        $request['opening_balance']    = $row['opening_balance'];    
        return $this->supplierStore($request);
    }
  
    public function supplierStore($request){  
        $supplier                   = new Supplier();
        $supplier->business_id      = business_id(); 
        $supplier->name             = $request->name;
        $supplier->company_name     = $request->company_name;
        $supplier->phone            = $request->phone;
        $supplier->email            = $request->email;
        $supplier->address          = $request->address;
        $supplier->opening_balance  = $request->opening_balance?? 0;
        $supplier->balance          = $request->opening_balance?? 0; 
        $supplier->save();
    }

    public function rules(): array
    {
        return [ 
                'name'           => ['required'],
                'phone'          => ['numeric','required'],  
                'opening_balance'=> ['numeric']
            ];
    }

}
