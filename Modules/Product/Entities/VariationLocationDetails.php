<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Branch\Entities\Branch;
use Modules\Pos\Entities\PosItem;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductVariation;
use Modules\Sell\Entities\SaleItem;
use Modules\Variation\Entities\Variation;

class VariationLocationDetails extends Model
{
    use HasFactory;

    protected $fillable = [];
    
   public function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
   }
   public function product(){
        return $this->belongsTo(Product::class,'product_id','id')->with(['unit','category','subcategory','upload']);
   } 
   public function ProductVariation(){
     return $this->belongsTo(ProductVariation::class,'product_variation_id','id');
   }
   public function variation(){
      return $this->belongsTo(Variation::class,'variation_id','id');
     }

   public function getCurrentStockPriceAttribute(){
      $total_price = $this->ProductVariation->sell_price_inc_tax * $this->qty_available;
      return number_format($total_price,2);
   }  
   public function saleItems(){
      return $this->hasMany(SaleItem::class,'vari_loc_det_id','id');
   }
   public function getSaleTotalUnitPriceAttribute(){
      return $this->saleItems->sum('total_unit_price');
   }

   public function posItems(){
      return $this->hasMany(PosItem::class,'vari_loc_det_id','id');
   }
   public function getPosTotalUnitPriceAttribute(){
      return $this->posItems->sum('total_unit_price');
   }
    
}
