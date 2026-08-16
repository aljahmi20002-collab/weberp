<?php
namespace Modules\BulkImport\Traits;

/**
 * 
 * return only filled value in array
 */
trait ReturnOnlyFilledArray
{

    //category request
    public function CategoryRequest($request){ 
        $data = null;
        if($request->data):
            foreach ($request->data as $key=>$value) {
                if(!empty($value[0])):
                    $data[$key]['name']       = $value[0];
                    $data[$key]['image_link'] = $value[1];
                    $data[$key]['description']= $value[2];
                    $data[$key]['position']   = $value[3];
                    $data[$key]['category']   = $value[4];
                endif;
            }   
        endif;
        return $data; 
    }
    //end category request


    //brand request
    public function BrandRequest($request){ 
        $data = null;
        if($request->data):
            foreach ($request->data as $key=>$value) {
                if(!empty($value[0])):
                    $data[$key]['name']            = $value[0];
                    $data[$key]['logo_image_link'] = $value[1];
                    $data[$key]['description']     = $value[2];
                    $data[$key]['position']        = $value[3];
                endif;
            }  
        endif; 
        return $data; 
    }
    //end brand request

    //bulk customer request
    public function CustomerRequest($request){ 
        $data = null;
        if($request->data):
            foreach ($request->data as $key=>$value) {
                if(!empty($value[0]) && !empty($value[2])): 
                    $data[$key]['name']              = $value[0];
                    $data[$key]['phone']             = $value[1];
                    $data[$key]['email']             = $value[2];
                    $data[$key]['image_link']        = $value[3];
                    $data[$key]['address']           = $value[4];
                    $data[$key]['opening_balance']   = $value[5];
                endif;
            }  
        endif; 
        return $data; 
    }
    //end bulk customer request
 
    //bulk supplier request
    public function SupplierRequest($request){ 
        $data = null;
        if($request->data):
            foreach ($request->data as $key=>$value) {
                if(!empty($value[0]) && !empty($value[2])):  
                    $data[$key]['name']              = $value[0];
                    $data[$key]['company_name']      = $value[1];
                    $data[$key]['phone']             = $value[2];
                    $data[$key]['email']             = $value[3]; 
                    $data[$key]['address']           = $value[4];
                    $data[$key]['opening_balance']   = $value[5];
                endif;
            }  
        endif; 
        return $data; 
    }
    //end bulk supplier request
  
    //bulk Product request
    public function ProductRequest($request){ 
        $products = null;
        if($request->data):
            foreach ($request->data as $key=>$value) { 
                if(
                        !empty($value[0]) && !empty($value[2]) &&
                        !empty($value[3]) && !empty($value[5]) &&
                        !empty($value[6]) && 
                        !empty($value[8]) && !empty($value[10]) &&
                        !empty($value[11]) &&  
                        !empty($value[13]) && !empty($value[14])
                    ):    
                    $products[$key]['name']            = $value[0];
                    $products[$key]['image_link']      = $value[1];
                    $products[$key]['unit_name']       = $value[2];
                    $products[$key]['brand_name']      = $value[3];
                    $products[$key]['warranty_name']   = $value[4];
                    $products[$key]['category_name']   = $value[5]; 
                    $products[$key]['subcategory_name']= $value[6];
                    if(business()):
                    $products[$key]['branch_name']     = $value[7];
                    endif;
                    $products[$key]['variation_name']  = $value[8];
                    $products[$key]['variation_values']= $value[9];
                    $products[$key]['quantity']        = $value[10];
                    $products[$key]['purchase_price']  = $value[11];
                    $products[$key]['profit_percent']  = $value[12];
                    $products[$key]['selling_price']   = $value[13];
                    $products[$key]['tax_name']        = $value[14];
                    $products[$key]['description']     = $value[15];
                endif;
            }  
        endif;   
        return $products; 
    }
    //end bulk Product request
 
}
