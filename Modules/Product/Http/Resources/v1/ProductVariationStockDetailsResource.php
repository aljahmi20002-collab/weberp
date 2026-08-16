<?php

namespace Modules\Product\Http\Resources\v1;
 
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request; 

class ProductVariationStockDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         
        return [
                "id"                          => $this->id,
                'sub_sku'                     => optional($this->ProductVariation)->sub_sku,
                'product_id'                  => $this->product->id,
                'product_name'                => $this->product->name.' - '.optional($this->variation)->name.' - '.optional($this->ProductVariation)->name, 
                'branch_name'                 => $this->branch->name,
                'unit_price_inc_tax'          => $this->ProductVariation->sell_price_inc_tax, 
                'current_stock'               => $this->qty_available.' '.$this->product->unit->short_name,
                'current_stock_price_inc_tax' => $this->CurrentStockPrice,
            ];
    }

}
