<?php

namespace Modules\Pos\Http\Resources\v1;
 
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariationLocationDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"            => $this->id,
            "image"         => @$this->product->image,
            'name'          => (string) @$this->product->name . ' - '.@$this->variation->name . ' - '.$this->ProductVariation->name,
            'sku'           => (string) @$this->ProductVariation->sub_sku,  
            'qty_available' => (int) $this->qty_available?? 0,  
            'unit_price'    => $this->ProductVariation->sell_price_inc_tax?? 0, 
        ];
    }
}
