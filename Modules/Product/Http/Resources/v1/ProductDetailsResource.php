<?php

namespace Modules\Product\Http\Resources\v1;

use App\Http\Resources\v1\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Modules\Branch\Http\Resources\v1\BranchResource;
use Modules\Product\Http\Resources\v1\ProductVariationResource;

class ProductDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
       
        return [
                "id"                        => $this->id,
                "name"                      => (string)$this->name,
                "sku"                       => (string)$this->sku,
                "purchase_price"            => (string)$this->VariationMultiplePurchasePrice,
                "selling_price"             => (string)$this->VariationMultipleSellingPrice, 
                "category"                  => (string)$this->category->name,
                "subcategory"               =>   optional($this->subcategory)->name,
                "brand"                     =>   optional($this->brand)->name,
                "warranty"                  =>   optional($this->warranty)->duration .' '. optional($this->warranty)->durationtypes,
                "available_quantity_unit"   => (string)$this->availableQuantity->sum('qty_available') .' '. optional($this->unit)->short_name,
                "images"                    => data_get($this->images,'image_one'),  
                "branchs"                   => $this->AllBranches->pluck('name','id')->all(),
                "description"               => $this->Description,
                "created_by"                => $this->user->name,
                "created_at"                => $this->created_at->format('d M Y'),
                "updated_at"                => $this->updated_at->format('d M Y'),
                'productVariations'         => ProductVariationResource::collection($this->productVariations),
                'productStockDetails'       => ProductVariationStockDetailsResource::collection($this->ProductVariationLocations)   
            ];
    }

}
