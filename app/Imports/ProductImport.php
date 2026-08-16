<?php

namespace App\Imports;

use App\Enums\Status;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel; 
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Modules\Business\Entities\Business;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductVariation;
use Modules\Product\Entities\VariationLocationDetails;
use Modules\TaxRate\Entities\TaxRate;

class ProductImport implements ToModel,WithHeadingRow,WithValidation,SkipsEmptyRows
{
    use Importable;
    /**
    * @param array $row
    */ 
 
   
     
    public function rules(): array
    { 
        if(!isUser()):
            $branch_id = ['required'];
        else:   
            $branch_id = '';
        endif; 
        return [ 
            'name'                    => ['required'],
            'unit_id'                 => ['required','numeric'],
            'brand_id'                => ['required','numeric'],
            'category_id'             => ['required','numeric'],
            'subcategory_id'          => ['required','numeric'],
            'warranty_id'             => ['numeric'],
            'branch_id'               => $branch_id,
            'variation_id'            => ['required'],
            'variation_value'         => ['required'], 
            'quantity'                => ['numeric'],
            'purchase_price'          => ['required','numeric'],
            'profit_percent'          => ['required','numeric'],
            'selling_price'           => ['required','numeric'],
            'tax_id'                  => ['required','numeric']
        ];
    }


    public function model(array $row)
    {  
        $request                       = new Request();
        if(!empty($row['alert_quantity'])):
            $request['enable_stock']   = 'on';
            Validator::make($row,[
                'alert_quantity'       =>['numeric']
            ])->validate();
        endif; 
        $request['name']                       = $row['name']; 
        $request['image_link']                 = $row['image_link']; 
        $request['unit_id']                    = $row['unit_id'];
        $request['brand_id']                   = $row['brand_id'];
        $request['category_id']                = $row['category_id'];
        $request['subcategory_id']             = $row['subcategory_id'];
        $request['warranty_id']                = $row['warranty_id'];
        $request['branches']                   = [$row['branch_id']];
        $request['variation_id']               = $row['variation_id'];
        $request['variation_values']           = [$row['variation_value']];
        $request['quantity']                   = $row['quantity'];
        $request['alert_quantity']             = $row['alert_quantity'];
        $request['default_purchase_price']     = $row['purchase_price'];
        $request['profit_percent']             = $row['profit_percent'];
        $request['selling_price']              = $row['selling_price'];
        $request['tax_id']                     = $row['tax_id'];
        $request['description']                = $row['description']; 
        return $this->ProductStore($request);
    }
  
    public function ProductStore($request){    
        if(isUser()):
            $request['branches'] = [Auth::user()->branch_id]; 
        endif; 
        $business_id            =  business_id(); 
        $business               =  Business::find($business_id); 
        $product                =  new Product();
        $product->business_id   =  $business_id; 
        $product->name          =  $request->name;
        if(!blank($business->sku_prefix)):
            $sku = $business->sku_prefix.'-'.$this->skuGenerate();
        else:
            $sku = $this->skuGenerate();
        endif;
        $product->sku                = $sku;
        $product->unit_id            = $request->unit_id;
        $product->brand_id           = $request->brand_id;
        $product->category_id        = $request->category_id;
        $product->subcategory_id     = $request->subcategory_id;
        $product->tax_id             = $request->tax_id;
        $product->warranty_id        = $request->warranty_id;
        $product->barcode_type       = $business->barcode_type;
        if($request->enable_stock):
            $product->enable_stock   = $request->enable_stock == 'on'? Status::ACTIVE:Status::INACTIVE;
            $product->alert_quantity = $request->alert_quantity;
        endif; 
        $product->default_quantity   = $request->quantity;
        $product->variation_id       = $request->variation_id;
        if($request->image_link):
            $product->image_id        =  $this->linktoImageUpload('product',$request->image_link);
        endif;
        $product->purchase_price = $request->default_purchase_price;
        $product->sell_price     = $request->selling_price;
        $product->description    = $request->description;
        $product->created_by     = Auth::user()->id;  
        $product->save(); 
        
        foreach($request->variation_values as $key=>$variationValue){
            $sub_sku = $product->sku.random_int(100,999);
            $productVariation  = $this->productVariation($request,$product,$variationValue,$sub_sku);
            foreach($request->branches as $branch_id){
                $this->VariationLocation($request,$product,$productVariation,$branch_id,$business_id);
            }
        }   
    }

    public function skuGenerate(){
        return random_int(100000,999999);
    }
 
    public function linktoImageUpload($folder='',$image){
        try { 
            $image_folder_path = public_path('uploads/'.$folder); 
            if($folder && !File::exists($image_folder_path)):
                File::makeDirectory($image_folder_path); 
            endif;
 
            //link to image upload
            $folder              = $folder !=null ? $folder.'/':'';
            $file                = file_get_contents($image); 
            $file_name           = date('YmdHisA').rand(100000,900000).'.jpg';
            File::put(public_path('uploads/'.$folder).$file_name, $file);
            $file_full_path      = 'uploads/'.$folder.$file_name;
            $upload              = new Upload();
            $upload->original    = $file_full_path; 
            $upload->image_one   = $file_full_path; 
            $upload->image_two   = $file_full_path; 
            $upload->image_three = $file_full_path; 
            $upload->save();
            //end link to image upload
            return $upload->id; 
        } catch (\Throwable $th) {
            return null;
        }
    }


    
    public function productvariation($request,$product,$variationValue,$sub_sku){ //.product variations
        $productVariation               = new ProductVariation();  
        $productVariation->sub_sku      = $sub_sku; 
        $productVariation->name         = $variationValue; 
        $productVariation->product_id   = $product->id;
        $productVariation->variation_id = $request->variation_id;
        $productVariation->default_purchase_price = $request->default_purchase_price;
        $productVariation->profit_percent         = $request->profit_percent;
        $productVariation->default_sell_price     = $request->selling_price;

        $tax                 = TaxRate::find($request->tax_id);
        if($request->selling_price != 0):
            $tax_amount          = ($request->selling_price/100) * $tax->tax_rate;
        else:
            $tax_amount      = 0;
        endif;
        $sell_price_inc_tax  = ($request->selling_price + $tax_amount); 
        $productVariation->sell_price_inc_tax = $sell_price_inc_tax; 
        $productVariation->save();
        return $productVariation; 
    }

    public function VariationLocation($request,$product,$productVariation,$branch_id,$business_id){ //variation location details
        $variationLocation               = new VariationLocationDetails();
        $variationLocation->business_id  = $business_id;
        $variationLocation->branch_id    = $branch_id;
        $variationLocation->product_id   = $product->id;
        $variationLocation->variation_id = $request->variation_id; 
        $variationLocation->product_variation_id = $productVariation->id;
        $variationLocation->qty_available = $request->quantity == ''? 0:$request->quantity;
        $variationLocation->save();
    }
 
}
