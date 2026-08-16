<?php
namespace Modules\Product\Traits;
 
trait BusinessWiseDataFetch
{
    public function unitOptions ($data){
        $options  = '<option disabled selected>'.__('select').' '. __('unit').'</option>';
        foreach ($data as  $unit) {
            $options   .= '<option value="' . $unit->id.'" >'.$unit->name.'</option>';
        }
        return $options;
    }
    public function categoryOptions ($data){
        $options  = '<option disabled selected>'.__('select').' '. __('category').'</option>';
        foreach ($data as  $category) {
            $options   .= '<option value="' . $category->id.'" >'.$category->name.'</option>';
        }
        return $options;
    }
    public function subcategoryOptions ($data){
        $options  = '<option value="">'.__('select').' '. __('subcategory').'</option>';
        foreach ($data as  $subcategory) {
            $options   .= '<option value="' . $subcategory->id.'" >'.$subcategory->name.'</option>';
        }
        return $options;
    }
    public function branchesOptions ($data){
        $options  = ''; 
        foreach ($data as  $branch) {
            $options   .= '<option value="' . $branch->id.'" >'.$branch->name.'</option>';
        }
        return $options;
    }
    public function applicableTaxOptions ($data){
        $options  = ''; 
        foreach ($data as  $tax) {
            $options   .= '<option value="' . $tax->id.'" >'.$tax->name.'</option>';
        }
        return $options;
    }
    public function brandsOptions ($data){
        $options  = ''; 
        foreach ($data as  $brand) {
            $options   .= '<option value="' . $brand->id.'" >'.$brand->name.'</option>';
        }
        return $options;
    }
    public function variationOptions ($data){ 
        $options  = ''; 
        foreach ($data as  $variation) {
            $options   .= '<option value="' . $variation->id.'" >'.$variation->name.'</option>';
        }
        return $options;
    }
    public function variationValueOptions ($data){ 
        $options  = ''; 
        if($data):
            foreach ($data->value as  $value) {
                if(!blank($value)):
                $options   .= '<option value="'.$value.'" selected >'.$value.'</option>';
                endif;
            }
        endif;
        return $options;
    }
}

 