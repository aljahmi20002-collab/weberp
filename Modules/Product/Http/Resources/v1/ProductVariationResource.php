<?php

namespace Modules\Product\Http\Resources\v1;
 
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request; 

class ProductVariationResource extends JsonResource
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
            'sub_sku'                   => $this->sub_sku, 
            'variation_name'            => $this->variation->name . ' - '.$this->name,
            'purchase_price'            => $this->default_purchase_price,
            'profit_percent'            => $this->profit_percent,
            'selling_price'             => $this->default_sell_price,
            'tax_amount'                => businessCurrency($this->business_id) .' '.($this->default_sell_price/100) * $this->product->taxRate->tax_rate,
            'sell_price_inc_tax'        => $this->sell_price_inc_tax, 
        ];
        
    }

}
