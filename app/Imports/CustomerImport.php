<?php

namespace App\Imports;
 
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToModel; 
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation; 
use Modules\Customer\Entities\Customer;

class CustomerImport implements ToModel,WithHeadingRow,WithValidation,SkipsEmptyRows
{
    use Importable;
    /**
    * @param array $row
    */ 
 
   
    public function model(array $row)
    {  
        $request                       = new Request();
        $request['name']               = $row['name'];   
        $request['phone']              = $row['phone'];
        $request['email']              = $row['email'];  
        $request['address']            = $row['address'];  
        $request['opening_balance']    = $row['opening_balance'];   
          
        return $this->customerStore($request);
    }
  
    public function customerStore($request){  
        $customer                   = new Customer();
        $customer->business_id      = business_id(); 
        $customer->name             = $request->name;
        $customer->phone            = $request->phone;
        $customer->email            = $request->email;
        $customer->address          = $request->address;
        $customer->opening_balance  = $request->opening_balance?? 0;
        $customer->balance          = $request->opening_balance?? 0; 
        $customer->save();
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
