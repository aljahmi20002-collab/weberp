<?php

namespace Modules\Product\Entities;

use App\Models\Upload;
use App\Models\User;
use App\Traits\CommonHelperTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Modules\Branch\Entities\Branch;
use Modules\Brand\Entities\Brand;
use Modules\Business\Entities\Business;
use Modules\Category\Entities\Category;
use Modules\TaxRate\Entities\TaxRate;
use Modules\Unit\Entities\Unit;
use Modules\Warranties\Entities\Warranty;

class Product extends Model
{
    use HasFactory,CommonHelperTrait;

    protected $fillable = [];

    public function business(){
        return $this->belongsTo(Business::class,'business_id','id');
    } 
    public function unit(){
        return $this->belongsTo(Unit::class,'unit_id','id');
    } 
    public function brand(){
        return $this->belongsTo(Brand::class,'brand_id','id');
    }
    public function category(){
        return $this->belongsTo(Category::class,'category_id','id')->where('parent_id',null);
    }  
    public function subcategory(){
        return $this->belongsTo(Category::class,'subcategory_id','id');
    } 
    public function taxRate(){
        return $this->belongsTo(TaxRate::class,'tax_id','id');
    } 
    public function upload(){
        return $this->belongsTo(Upload::class,'image_id','id');
    } 

    public function getImageAttribute(){
        if($this->upload && $this->upload && File::exists(public_path($this->upload->original['image_three']))):
            return static_asset($this->upload->original['image_three']);
        else:
            return static_asset('backend/images/default/blank-img.jpg');
        endif;
    }

    public function getImagesAttribute()
    { 
        $defaultImages = [
            "original"      => static_asset('backend/images/default/product/original.jepg'),
            "image_one"     => static_asset('backend/images/default/product/1.webp'),
            "image_two"     => static_asset('backend/images/default/product/2.webp'),
            "image_three"   => static_asset('backend/images/default/product/3.webp')
        ]; 
        return $this->modelImageProcessor($this->upload, $defaultImages);
    }
 
    public function user(){
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function warranty(){
        return $this->belongsTo(Warranty::class,'warranty_id','id');
    }

    public function availableQuantity(){
        return $this->hasMany(VariationLocationDetails::class,'product_id','id')->where(function($query){
            if(isUser()):
                $query->where('branch_id',Auth::user()->branch_id);
            endif;
        });
    }
    public function branchs(){
        return $this->hasMany(VariationLocationDetails::class, 'product_id','id');
    }

    public function getAllBranchesIdsAttribute(){
        $branch_ids=[];
        $branchs = $this->branchs->groupBy('branch_id'); 
        foreach($branchs as $key=>$branch){
            $branch_ids[] = $key;
        }
        return $branch_ids;
    }
    public function getAllBranchesAttribute(){
        $branch_ids=[];
        $branchs = $this->branchs->groupBy('branch_id'); 
        foreach($branchs as $key=>$branch){
            $branch_ids[] = $key;
        } 
        return Branch::whereIn('id',array_unique($branch_ids))->get();
    }

    public function profitPercent(){
        return $this->belongsTo(ProductVariation::class,'id','product_id' );
    }
    
    public function productVariations(){
        return $this->hasMany(ProductVariation::class,'product_id','id');
    }

    public function ProductVariationLocations(){
        return $this->hasMany(VariationLocationDetails::class, 'product_id','id')->where(function($query){
            if(isUser()):
                $query->where('branch_id',Auth::user()->branch->id);
            endif;
        });
    }

    public function VariationLocations(){
        return $this->hasMany(VariationLocationDetails::class, 'product_id','id');
    }

    public function getVariationMultiplePurchasePriceAttribute(){
        $purchasePrices = $this->productVariations->pluck('default_purchase_price')->toArray();
        $multiple_price = '';
        $i=0;
        foreach (array_unique($purchasePrices) as  $price) { 
            ++$i;
            $multiple_price .= businessCurrency($this->business_id).' '.$price; 
            if($i<count(array_unique($purchasePrices))):
                $multiple_price .=', ';
            endif;
        }
        return $multiple_price;
    } 
    
    public function getVariationMultipleSellingPriceAttribute(){//selling price not incluede tax
        $sellingPrices = $this->productVariations->pluck('default_sell_price')->toArray();
        $multiple_sell_price = '';
        $i=0;
        foreach (array_unique($sellingPrices) as  $price) { 
            ++$i;
            $multiple_sell_price .= businessCurrency($this->business_id).' '.$price; 
            if($i<count(array_unique($sellingPrices))):
                $multiple_sell_price .=', ';
            endif;
        }
        return $multiple_sell_price;
    }
}
