<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Config;
use Modules\Product\Entities\Product;
use Modules\Variation\Entities\Variation;
use DNS1D;
// use DNS2D;
class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    public function product (){
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function variation(){
        return $this->belongsTo(Variation::class,'variation_id','id');
    }

    public function getSubBarcodePrintAttribute()
    { 
       $sub_sku = $this->sub_sku; 
       return DNS1D::getBarcodeHTML($sub_sku, Config::get('pos_default.barcode_types.'.$this->product->barcode_type));
    }
    
    public function getItemNameAttribute(){
        return $this->product->name.'-'.$this->name.'- ('.$this->sub_sku.')';
    }
}
